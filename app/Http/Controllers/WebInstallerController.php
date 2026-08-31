<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\WebStyle;
use App\Models\Banner;
use App\Models\Content;
use App\Models\Website;
use App\Models\Message;
use App\Models\Service;
use App\Models\Product;
use App\Models\Package;
use App\Models\Bank;
use App\Models\Transaction;
use App\Models\TransactionAttachment;
use App\Services\Masterdata\CartService;
use App\Services\Website\AboutService;
use App\Models\WebSection;
use App\Services\Masterdata\DeliveryPriceService;
use App\Services\Masterdata\TransactionService;
use App\Services\Helper\UploadService;

/**
 * Public site for website 2 (GLS Instalasi), served at /installer.
 * Views live under resources/views/{layout,pages}/website2.
 */
class WebInstallerController extends Controller
{
    /** This site's website. */
    protected $websiteId = 2;

    /** URL + route-name prefix this site is served under (see routes/web.php). */
    protected $routePrefix = 'installer';

    /** Views this site renders. Website 3 reuses them, hence a property. */
    protected $viewPrefix = 'pages.website2';

    public function __construct()
    {
        // Make this the "current" website so the web_property() helper matches.
        if (Schema::hasTable('websites')) {
            Website::setCurrent(Website::find($this->websiteId));
        }

        view()->share('website', Schema::hasTable('websites') ? Website::find($this->websiteId) : null);

        $styles = Schema::hasTable('web_styles') ? WebStyle::asMap($this->websiteId) : [];
        view()->share('styles', $styles);

        $banners = Schema::hasTable('banners')
            ? Banner::active()->where('website_id', $this->websiteId)->get()
            : collect();
        view()->share('banners', $banners);

        $contents = Schema::hasTable('contents')
            ? Content::active()->where('website_id', $this->websiteId)->get()
            : collect();
        view()->share('contents', $contents);

        // Every link and endpoint in the views is built from this, so the same
        // views serve /installer and /ev without knowing which one they are.
        view()->share('site', $this->routePrefix);

        // Which landing-page blocks this site shows, and in what order.
        // Shared (not just passed to index) because the layout builds its menu
        // from the same rows on every page.
        // onPage() as well as inNav(): these are in-page anchors, so a link to
        // a section that is not rendered would just be a dead jump.
        $navSections = Schema::hasTable('web_sections')
            ? WebSection::forWebsite($this->websiteId)->inNav()->onPage()->ordered()->get()
            : collect();
        view()->share('navSections', $navSections);

        // The layout hides its "Paket" link when the site sells no packages,
        // so it needs the answer on every page, not just the landing page.
        $hasPackages = Schema::hasTable('packages')
            ? Package::where('website_id', $this->websiteId)->active()->exists()
            : false;
        view()->share('hasPackages', $hasPackages);

        // Where the hero's call to action points. The catalog is the natural
        // target, but a site can switch that section off (Website > Landing
        // Sections), so fall back to the packages and then to whatever the page
        // does start with. A section that renders nothing is not a target: the
        // packages block hides itself when the site has no packages.
        $onPage = Schema::hasTable('web_sections')
            ? WebSection::forWebsite($this->websiteId)->onPage()->ordered()->get()
            : collect();

        $reachable = $onPage
            ->filter(fn ($s) => $s->anchor)
            ->reject(fn ($s) => $s->section_key === 'packages' && !$hasPackages);

        $heroAnchor = optional(
            $reachable->firstWhere('section_key', 'products')
                ?? $reachable->firstWhere('section_key', 'packages')
                ?? $reachable->first()
        )->anchor;

        view()->share('heroAnchor', $heroAnchor);
    }

    /**
     * Fully-qualified route name for this site, e.g. 'installer.checkout'.
     */
    protected function routeName(string $name): string
    {
        return $this->routePrefix . '.' . $name;
    }

    /**
     * View name for this site, e.g. 'pages.website2.checkout'.
     */
    protected function viewName(string $name): string
    {
        return $this->viewPrefix . '.' . $name;
    }

    public function index()
    {
        $services = Service::where('website_id', $this->websiteId)
            ->where('is_active', true)
            ->orderBy('service_name')
            ->get();

        // The catalog shown in full on the landing page: every active product
        // of every active service, with its image, sizes and specifications.
        // Grouped by service_id so the section can follow the service order.
        $products = Product::whereIn('service_id', $services->pluck('id'))
            ->where('is_active', true)
            ->with([
                'images' => fn ($q) => $q->where('is_active', true),
                'variants' => fn ($q) => $q->where('is_active', true),
                'specifications',
            ])
            ->orderBy('id')
            ->get()
            ->groupBy('service_id');

        // The blocks this landing page is built from, in order. The view
        // includes one partial per row, so the table decides the layout.
        $sections = Schema::hasTable('web_sections')
            ? WebSection::forWebsite($this->websiteId)->onPage()->ordered()->get()
            : collect();

        // The About section, in display order; the first row is the main one.
        $abouts = app(AboutService::class)->getPublicList($this->websiteId);

        // Pricing tiers, cheapest first, with their contents for the feature
        // list and the product images the cards use as their cover.
        $packages = Package::with([
                'details.product.images' => fn ($q) => $q->where('is_active', true),
                'details.product.specifications',
                'details.otherCharge',
            ])
            ->where('website_id', $this->websiteId)
            ->active()
            ->orderBy('package_price')
            ->get();

        return view($this->viewName('index'), compact('sections', 'services', 'products', 'packages', 'abouts'));
    }

    public function content($id)
    {
        $content = Content::where('id', $id)
            ->where('is_active', true)
            ->where('website_id', $this->websiteId)
            ->first();

        if (!$content) {
            abort(404);
        }

        return view($this->viewName('content'), ['content' => $content]);
    }

    /**
     * Checkout prototype: list services + products + packages with a sticky cart.
     */
    public function checkout()
    {
        $services = Service::where('website_id', $this->websiteId)
            ->where('is_active', true)
            ->orderBy('service_name')
            ->get();

        $products = Product::whereHas('service', fn ($q) => $q->where('website_id', $this->websiteId))
            ->where('is_active', true)
            ->with(['images', 'variants' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('id')
            ->get();

        // Same pricing tiers as the landing page, so the "Paket" tab mirrors it.
        $packages = Package::with(['details.product', 'details.otherCharge'])
            ->where('website_id', $this->websiteId)
            ->active()
            ->orderBy('package_price')
            ->get();

        $provinces = app(DeliveryPriceService::class)->provinces();

        // The cart saved in this browser, re-priced from the catalogue. Also
        // mints the cart cookie, so the first add already has a cart to go to.
        $carts     = app(CartService::class);
        $carts->tokenOrMint();
        $cartLines = $carts->displayLines($this->websiteId);

        return view($this->viewName('checkout'), compact('services', 'products', 'packages', 'provinces', 'cartLines'));
    }

    /* ---- Saved cart (guest, keyed on the cart_token cookie) ---- */

    /**
     * The saved cart, re-priced. Lets any page read the cart without
     * rendering it server-side (a header badge, for instance).
     */
    public function cartShow(CartService $carts)
    {
        $lines = $carts->displayLines($this->websiteId);

        return response()->json([
            'items' => $lines,
            'count' => array_sum(array_column($lines, 'qty')),
        ]);
    }

    /**
     * Save the cart for this browser. The posted basket replaces whatever was
     * stored; prices are never taken from the request, only the keys and
     * quantities, and unknown keys are dropped.
     */
    public function cartSave(Request $request, CartService $carts)
    {
        $data = $request->validate([
            'items'        => 'present|array|max:' . CartService::MAX_LINES,
            'items.*.id'   => 'required|string|max:60',
            'items.*.qty'  => 'nullable|integer|min:1|max:' . CartService::MAX_QTY,
        ]);

        $carts->save($this->websiteId, $data['items']);

        $lines = $carts->displayLines($this->websiteId);

        return response()->json([
            'saved' => true,
            'items' => $lines,
            'count' => array_sum(array_column($lines, 'qty')),
        ]);
    }

    /**
     * Empty the cart for this browser.
     */
    public function cartClear(CartService $carts)
    {
        $carts->clear($this->websiteId);

        return response()->json(['saved' => true, 'items' => [], 'count' => 0]);
    }

    /* ---- Public region lookups for the checkout address form ---- */

    public function regionCities($provinceCode)
    {
        return response()->json(app(DeliveryPriceService::class)->citiesByProvince($provinceCode));
    }

    public function regionDistricts($cityCode)
    {
        return response()->json(app(DeliveryPriceService::class)->districtsByCity($cityCode));
    }

    public function regionSubdistricts($districtCode)
    {
        return response()->json(app(DeliveryPriceService::class)->subdistrictsByDistrict($districtCode));
    }

    /**
     * Return the delivery fare for the chosen area (city + optional
     * district/subdistrict), applying the subdistrict -> district -> city
     * fallback.
     */
    public function deliveryFare(Request $request)
    {
        $row = app(DeliveryPriceService::class)->resolveFare(
            $this->websiteId,
            $request->query('city_code'),
            $request->query('district_code'),
            $request->query('subdistrict_code')
        );

        return response()->json([
            'found' => (bool) $row,
            'fare'  => $row ? (float) $row->price : null,
            'level' => $row ? $row->level : null,
        ]);
    }

    /**
     * Order confirmation page. Rebuilds the cart from product/variant ids
     * (prices are re-read server-side, never trusted from the client) and
     * recomputes the delivery fare from the chosen address.
     */
    public function confirm(Request $request)
    {
        $data  = $this->validateCheckout($request);
        $order = $this->buildOrder($data);

        if (is_string($order)) {
            // buildOrder returns an error message string on failure.
            return redirect()->route($this->routeName('checkout'))->with('failed', $order);
        }

        return view($this->viewName('checkout_confirm'), [
            'items'    => $order['items'],
            'subtotal' => $order['subtotal'],
            'discount' => $order['discount'],
            'fare'     => $order['fare'],
            'total'    => $order['total'],
            'address'  => $order['address'],
            // Raw validated input, re-submitted verbatim to place() so the
            // order is rebuilt & re-priced server-side (never trusted from view).
            'raw'      => $data,
        ]);
    }

    /**
     * Shared checkout validation for confirm() and place().
     */
    protected function validateCheckout(Request $request): array
    {
        return $request->validate([
            'cart_json'        => 'required|string',
            'province_code'    => ['nullable', 'string'],
            'city_code'        => ['required', 'string', \Illuminate\Validation\Rule::exists('glb_cities', 'city_code')],
            'district_code'    => ['required', 'string', \Illuminate\Validation\Rule::exists('glb_districts', 'district_code')->where(fn ($q) => $q->where('city_code', $request->input('city_code')))],
            'subdistrict_code' => ['nullable', 'string', \Illuminate\Validation\Rule::exists('glb_subdistricts', 'subdistrict_code')->where(fn ($q) => $q->where('district_code', $request->input('district_code')))],
            'address_detail'   => 'required|string|max:1000',
            'recipient_name'   => 'required|string|max:191',
            'phone'            => 'required|string|max:30',
            'email'            => 'required|email|max:191',
        ]);
    }

    /**
     * Rebuild the cart from product/variant/package ids (prices re-read server-side),
     * resolve the delivery fare and address names. Returns an array with keys
     * items/subtotal/fare/fareRow/total/address, or an error message string.
     *
     * @return array|string
     */
    protected function buildOrder(array $data)
    {
        $lines = json_decode($data['cart_json'], true);
        if (!is_array($lines) || empty($lines)) {
            return 'Keranjang kosong.';
        }

        // CartService is the single place a cart key becomes a price, so a
        // saved cart and the order built from it cannot disagree.
        $rows = app(CartService::class)->resolveItems($this->websiteId, $lines);

        $items       = [];   // display rows (confirmation page)
        $detailRows  = [];   // transaction_details
        $packageRows = [];   // transaction_packages
        $chargeRows  = [];   // transaction_charges
        $benefitRows = [];   // transaction_benefits

        $subtotal = 0;       // gross: package prices before discount + product prices
        $discount = 0;       // sum of the package discounts

        foreach ($rows as $row) {
            $qty = (int) $row['qty'];

            /* ---- Package: priced in full here, its contents exploded at 0 ---- */
            if ($row['kind'] === 'package') {
                $package = $row['package'];

                $gross     = (float) $row['price'];
                $lineDisc  = (float) $row['discount'] * $qty;
                $lineTotal = ($gross * $qty) - $lineDisc;

                $subtotal += $gross * $qty;
                $discount += $lineDisc;

                $packageRows[] = [
                    'package_id'   => $package->id,
                    'package_code' => $package->package_code,
                    'package_name' => $package->package_name,
                    'qty'          => $qty,
                    'price'        => $gross,
                    'discount'     => $lineDisc,
                    'subtotal'     => $lineTotal,
                ];

                // Marks every exploded row as belonging to this package.
                $note     = 'Termasuk paket: ' . $package->package_name;
                $included = [];

                foreach ($package->details as $d) {
                    $rowQty = max(1, (int) $d->qty) * $qty;
                    $label  = $d->line_label;

                    $included[] = ($rowQty > 1 ? $rowQty . 'x ' : '') . $label;

                    if ($d->line_type === 'product') {
                        $detailRows[] = [
                            'product_id'         => $d->product_id,
                            'product_variant_id' => null,
                            'product_code'       => optional($d->product)->products_code,
                            'product_name'       => $label,
                            'variant_name'       => null,
                            'qty'                => $rowQty,
                            'price'              => 0,
                            'discount'           => 0,
                            'subtotal'           => 0,
                            'remark'             => $note,
                        ];
                    } elseif ($d->line_type === 'charge') {
                        $chargeRows[] = [
                            'other_charge_id' => $d->other_charge_id,
                            'code'            => optional($d->otherCharge)->code,
                            'name'            => $label,
                            'amount'          => 0,
                            'remark'          => $note,
                        ];
                    } else {
                        $benefitRows[] = [
                            'package_id'   => $package->id,
                            'package_name' => $package->package_name,
                            'benefit_name' => $label,
                            'qty'          => $rowQty,
                            'price'        => 0,
                            'discount'     => 0,
                            'subtotal'     => 0,
                            'remark'       => $note,
                        ];
                    }
                }

                $items[] = [
                    'kind'     => 'package',
                    'name'     => $package->package_name,
                    'code'     => $package->package_code,
                    'included' => $included,
                    'price'    => $gross,
                    'qty'      => $qty,
                    'discount' => $lineDisc,
                    'total'    => $lineTotal,
                ];

                continue;
            }

            /* ---- Product / variant / bare service ---- */
            $product = $row['product'];
            $variant = $row['variant'];

            $price     = (float) $row['price'];
            $lineTotal = $price * $qty;
            $subtotal += $lineTotal;

            $detailRows[] = [
                'product_id'         => optional($product)->id,
                'product_variant_id' => optional($variant)->id,
                'product_code'       => optional($product)->products_code,
                'product_name'       => $product ? $product->products_name : optional($row['service'])->service_name,
                'variant_name'       => optional($variant)->variant_name,
                'qty'                => $qty,
                'price'              => $price,
                'discount'           => 0,
                'subtotal'           => $lineTotal,
            ];

            $items[] = [
                'kind'     => 'product',
                'name'     => $row['name'],
                'code'     => optional($product)->products_code,
                'included' => [],
                'price'    => $price,
                'qty'      => $qty,
                'discount' => 0,
                'total'    => $lineTotal,
            ];
        }

        if (empty($items)) {
            return 'Produk tidak ditemukan. Silakan pilih ulang.';
        }

        // Authoritative fare from the address (subdistrict -> district -> city).
        $fareRow = app(DeliveryPriceService::class)->resolveFare(
            $this->websiteId,
            $data['city_code'],
            $data['district_code'] ?? null,
            $data['subdistrict_code'] ?? null
        );
        $fare = $fareRow ? (float) $fareRow->price : null;

        $address = [
            'recipient_name'   => $data['recipient_name'],
            'phone'            => $data['phone'],
            'email'            => $data['email'],
            'detail'           => $data['address_detail'],
            'province_code'    => $data['province_code'] ?? null,
            'city_code'        => $data['city_code'],
            'district_code'    => $data['district_code'] ?? null,
            'subdistrict_code' => $data['subdistrict_code'] ?? null,
            'province'         => !empty($data['province_code']) ? DB::table('glb_provinces')->where('province_code', $data['province_code'])->value('province_name') : null,
            'city'             => DB::table('glb_cities')->where('city_code', $data['city_code'])->value('city_name'),
            'district'         => DB::table('glb_districts')->where('district_code', $data['district_code'])->value('district_name'),
            'subdistrict'      => !empty($data['subdistrict_code']) ? DB::table('glb_subdistricts')->where('subdistrict_code', $data['subdistrict_code'])->value('subdistrict_name') : null,
        ];

        // Charges that ride inside a package are 0, so this is 0 today; summed
        // anyway so a priced charge would still reach transactions.charges.
        $chargeTotal = array_sum(array_column($chargeRows, 'amount'));

        return [
            'items'       => $items,        // display
            'details'     => $detailRows,   // transaction_details
            'packages'    => $packageRows,  // transaction_packages
            'charges'     => $chargeRows,   // transaction_charges
            'benefits'    => $benefitRows,  // transaction_benefits
            'subtotal'    => $subtotal,
            'discount'    => $discount,
            'chargeTotal' => $chargeTotal,
            'fare'        => $fare,
            'fareRow'     => $fareRow,
            'total'       => $subtotal - $discount + ($fare ?? 0) + $chargeTotal,
            'address'     => $address,
        ];
    }

    /**
     * Place the order: persist the transaction (header + details + address)
     * server-side, then redirect to the public receipt so a refresh does not
     * re-submit.
     */
    public function place(Request $request, TransactionService $transactions)
    {
        $data  = $this->validateCheckout($request);
        $order = $this->buildOrder($data);

        if (is_string($order)) {
            return redirect()->route($this->routeName('checkout'))->with('failed', $order);
        }

        // price is the gross (packages at their full price); the package
        // discounts are collected on the header, per the transactions formula:
        // total = price - discount + delivery_fee + charges.
        $subtotal = $order['subtotal'];
        $discount = $order['discount'];
        $charges  = $order['chargeTotal'];
        $fare     = $order['fare'] ?? 0;
        $total    = $subtotal - $discount + $fare + $charges;

        $header = [
            'website_id'      => $this->websiteId,
            'transaction_date' => now()->toDateString(),
            'customer_name'   => $order['address']['recipient_name'],
            'customer_email'  => $order['address']['email'],
            'customer_phone'  => $order['address']['phone'],
            'price'           => $subtotal,
            'discount'        => $discount,
            'delivery_fee'    => $fare,
            'charges'         => $charges,
            'total'           => $total,
            'tax'             => 0,
            'grandtotal'      => $total,
            'status'          => 'STSPY', // Menunggu Pembayaran
            'created_by'      => $order['address']['email'],
            'hist_description' => 'Pesanan dibuat melalui checkout',
        ];

        // Stamp the buyer on every row set the service writes.
        $stamp = fn (array $rows) => array_map(
            fn ($row) => $row + ['created_by' => $order['address']['email']],
            $rows
        );

        $address = [
            'recipient_name'   => $order['address']['recipient_name'],
            'recipient_phone'  => $order['address']['phone'],
            'province_code'    => $order['address']['province_code'],
            'city_code'        => $order['address']['city_code'],
            'district_code'    => $order['address']['district_code'],
            'subdistrict_code' => $order['address']['subdistrict_code'],
            'province_name'    => $order['address']['province'],
            'city_name'        => $order['address']['city'],
            'district_name'    => $order['address']['district'],
            'subdistrict_name' => $order['address']['subdistrict'],
            'address_detail'   => $order['address']['detail'],
            'delivery_fee'     => $fare,
        ];

        $result = $transactions->store(
            $header,
            $stamp($order['details']),    // products, package contents at 0
            $address,
            $stamp($order['charges']),    // other charges, 0 inside a package
            $stamp($order['packages']),   // packages, at full price + discount
            $stamp($order['benefits'])    // perks, always 0
        );

        if ($result['status'] !== 'success') {
            // Cart deliberately left alone: the buyer can retry from it.
            return redirect()->route($this->routeName('checkout'))->with('failed', $result['message']);
        }

        // Ordered, so the saved cart has done its job.
        app(CartService::class)->clear($this->websiteId);

        return redirect()->route($this->routeName('receipt'), ['token' => $result['data']->receipt_token]);
    }

    /**
     * Public receipt / invoice page for a placed order, addressed by its
     * unguessable token.
     */
    public function receipt($token, TransactionService $transactions)
    {
        $trx = $transactions->getByToken($token, $this->websiteId);

        if (!$trx) {
            abort(404);
        }

        $banks = Bank::where('website_id', $this->websiteId)
            ->where('is_active', true)
            ->orderBy('bank_name')
            ->get();

        return view($this->viewName('receipt'), [
            'trx'   => $trx,
            'banks' => $banks,
        ]);
    }

    /**
     * Customer-facing upload of a transaction attachment (payment proof, etc.)
     * from the receipt page, addressed by the order's public token.
     */
    public function uploadAttachment($token, Request $request, TransactionService $transactions, UploadService $upload)
    {
        $trx = $transactions->getByToken($token, $this->websiteId);
        if (!$trx) {
            abort(404);
        }

        $data = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
            'type' => ['nullable', \Illuminate\Validation\Rule::in(array_keys(TransactionAttachment::TYPES))],
            'note' => 'nullable|string|max:500',
        ]);

        // Capture file metadata BEFORE storing: UploadService moves the temp
        // file, after which getSize()/getMimeType() would stat a missing path.
        $file         = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $mime         = $file->getClientMimeType();
        $size         = $file->getSize();

        $up = $upload->store($file, 'transaction');
        if (!$up) {
            return back()->with('failed', 'Gagal mengunggah berkas. Silakan coba lagi.');
        }

        TransactionAttachment::create([
            'transaction_id' => $trx->id,
            'type'           => $data['type'] ?? 'PAYMENT',
            'file_path'      => $up['uploaded'],
            'original_name'  => $up['original'] ?? $originalName,
            'mime'           => $mime,
            'size'           => $size,
            'note'           => $data['note'] ?? null,
            'uploaded_by'    => $trx->customer_email ?: 'customer',
        ]);

        return redirect()
            ->route($this->routeName('receipt'), ['token' => $token])
            ->with('success', 'Bukti berhasil diunggah. Terima kasih!');
    }

    public function done()
    {
        return view($this->viewName('checkout_done'));
    }

    /**
     * "Pesanan Anda" — order lookup form.
     */
    public function orders()
    {
        return view($this->viewName('orders'), [
            'searched' => false,
            'orders'   => collect(),
            'email'    => '',
        ]);
    }

    /**
     * Look up a customer's orders by email + last 4 digits of phone.
     * Lightweight verification (not a login): both must match.
     */
    public function ordersLookup(Request $request)
    {
        $data = $request->validate([
            'email'       => 'required|email|max:191',
            'phone_last4' => 'required|digits:4',
        ]);

        $orders = Transaction::where('website_id', $this->websiteId)
            ->where('customer_email', $data['email'])
            ->whereRaw('RIGHT(TRIM(customer_phone), 4) = ?', [$data['phone_last4']])
            ->with('statusCode')
            ->orderByDesc('id')
            ->get();

        return view($this->viewName('orders'), [
            'searched' => true,
            'orders'   => $orders,
            'email'    => $data['email'],
        ]);
    }

    public function sendMessage(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:191',
            'email'   => 'required|email|max:191',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:191',
            'message' => 'required|string|max:5000',
        ]);

        $data['website_id'] = $this->websiteId;
        $data['status']     = 'open';

        Message::create($data);

        return back()
            ->with('message_sent', 'Your message has been sent. Thank you!')
            ->withFragment('contact');
    }
}
