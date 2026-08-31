<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebSectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Which blocks each catalog site's landing page is built from, in what
     * order, and whether they show on the page and in the menu.
     *
     * section_key must match a partial under
     * resources/views/pages/website2/sections/, which is why these rows are
     * seeded and edited rather than created from the admin.
     *
     * This seeder is authoritative: it writes the order and both visibility
     * flags every time, so running it restores exactly the arrangement below
     * and discards anything rearranged in Website > Landing Sections. That is
     * what makes it a reproducible default; re-generate it from the live
     * database when the admin's arrangement is the one worth keeping.
     *
     * @return void
     */
    public function run()
    {
        // Labels are shared: only the arrangement differs per website.
        $labels = [
            //  key            => [section_name,           nav_label,       anchor]
            'packages'     => ['Paket & Harga',        'Paket',        'packages'],
            'products'     => ['Produk & Layanan',     'Produk',       'products'],
            'about'        => ['Tentang Kami',         'About',        'about'],
            'why-us'       => ['Mengapa Memilih Kami', 'Mengapa Kami', 'why-us'],
            'clients'      => ['Clients',              'Clients',      'clients'],
            'testimonials' => ['News & Articles',      'Articles',     'articles'],
            'orders'       => ['Pesanan Anda',         'Pesanan Anda', 'orders'],
            'contact'      => ['Contact',              'Contact',      'contact'],
        ];

        // Per website: key => [order, show_in_page, show_in_nav].
        $sites = [
            // Website 2 (Furniture/AC) leads with its packages; the product
            // catalog is switched off here.
            2 => [
                'packages'     => [0, true,  true],
                'products'     => [1, true, true],
                'about'        => [2, true,  true],
                'why-us'       => [3, true,  true],
                'clients'      => [4, false, false],
                'testimonials' => [5, false, false],
                'orders'       => [6, false, false],
                'contact'      => [7, true,  true],
            ],

            // Website 3 (EV Charging) shows both packages and the catalog.
            3 => [
                'packages'     => [0, true,  true],
                'products'     => [1, false,  false],
                'about'        => [2, true,  true],
                'why-us'       => [3, true,  true],
                'clients'      => [4, false, false],
                'testimonials' => [5, false, false],
                'orders'       => [6, false, false],
                'contact'      => [7, true,  true],
            ],
        ];

        foreach ($sites as $websiteId => $arrangement) {
            foreach ($arrangement as $key => [$order, $onPage, $inNav]) {
                [$name, $navLabel, $anchor] = $labels[$key];

                DB::table('web_sections')->updateOrInsert(
                    ['website_id' => $websiteId, 'section_key' => $key],
                    [
                        'section_name' => $name,
                        'nav_label'    => $navLabel,
                        'anchor'       => $anchor,
                        'order'        => $order,
                        'show_in_page' => $onPage,
                        'show_in_nav'  => $inNav,
                    ]
                );
            }

            $shown = collect($arrangement)->filter(fn ($a) => $a[1])->keys()->implode(', ');
            $this->command->info("  website $websiteId on the page: $shown");
        }
    }
}
