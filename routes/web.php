<?php

use App\Http\Controllers\PortalController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\WebInstallerController;
use App\Http\Controllers\WebEvController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\Masterdata\UserController;
use App\Http\Controllers\Admin\Masterdata\GroupMenuController;
use App\Http\Controllers\Admin\Masterdata\ServiceController;
use App\Http\Controllers\Admin\Masterdata\CategoryController;
use App\Http\Controllers\Admin\Masterdata\ProductController;
use App\Http\Controllers\Admin\Masterdata\PackageController;
use App\Http\Controllers\Admin\Masterdata\DeliveryPriceController;
use App\Http\Controllers\Admin\Masterdata\OtherChargeController;
use App\Http\Controllers\Admin\Masterdata\TransactionController;
use App\Http\Controllers\Admin\Masterdata\BankController;
use App\Http\Controllers\Admin\Website\WebStyleController;
use App\Http\Controllers\Admin\Website\WebsiteController;
use App\Http\Controllers\Admin\Website\AboutController;
use App\Http\Controllers\Admin\Website\WebSectionController;
use App\Http\Controllers\Admin\Website\BannerController;
use App\Http\Controllers\Admin\Website\ContentController;
use Illuminate\Support\Facades\Route;

// Portal: the main index is only a banner and one option per website.
Route::get('/', [PortalController::class, 'index'])->name('portal');

// First website (GLS Kontrak Logistic). It used to live at "/", which the
// portal now occupies; the route names are unchanged, so every route('home')
// and route('content.read') in its views still resolves.
Route::get('/logistic', [WebController::class, 'index'])->name('home');
Route::get('/logistic/content/{id}', [WebController::class, 'content'])->name('content.read');
Route::post('/logistic/contact', [WebController::class, 'sendMessage'])->name('contact.send');

// Odisys Logistic API
Route::get('/tracking', [ApiController::class, 'tracking']);
Route::post('/provinces', [ApiController::class, 'provinces']);
Route::post('/cities', [ApiController::class, 'cities']);
Route::post('/geo', [ApiController::class, 'geo']);
Route::post('/rates', [ApiController::class, 'rates']);

// Second website (GLS Instalasi)
Route::get('/installer', [WebInstallerController::class, 'index'])->name('installer.home');
Route::get('/installer/checkout', [WebInstallerController::class, 'checkout'])->name('installer.checkout');
Route::post('/installer/checkout/confirm', [WebInstallerController::class, 'confirm'])->name('installer.checkout.confirm');
Route::post('/installer/checkout/place', [WebInstallerController::class, 'place'])->name('installer.checkout.place');
Route::get('/installer/checkout/done', [WebInstallerController::class, 'done'])->name('installer.checkout.done');
Route::get('/installer/orders', [WebInstallerController::class, 'orders'])->name('installer.orders');
Route::post('/installer/orders', [WebInstallerController::class, 'ordersLookup'])->name('installer.orders.lookup');
Route::get('/installer/receipt/{token}', [WebInstallerController::class, 'receipt'])->name('installer.receipt');
Route::post('/installer/receipt/{token}/upload', [WebInstallerController::class, 'uploadAttachment'])->name('installer.receipt.upload');
// Saved cart for guests (keyed on the cart_token cookie, no login)
Route::get('/installer/cart', [WebInstallerController::class, 'cartShow'])->name('installer.cart.show');
Route::post('/installer/cart', [WebInstallerController::class, 'cartSave'])->name('installer.cart.save');
Route::delete('/installer/cart', [WebInstallerController::class, 'cartClear'])->name('installer.cart.clear');
// Checkout address cascade + fare (public)
Route::get('/installer/regions/cities/{provinceCode}', [WebInstallerController::class, 'regionCities'])->name('installer.regions.cities');
Route::get('/installer/regions/districts/{cityCode}', [WebInstallerController::class, 'regionDistricts'])->name('installer.regions.districts');
Route::get('/installer/regions/subdistricts/{districtCode}', [WebInstallerController::class, 'regionSubdistricts'])->name('installer.regions.subdistricts');
Route::get('/installer/fare', [WebInstallerController::class, 'deliveryFare'])->name('installer.fare');
Route::get('/installer/content/{id}', [WebInstallerController::class, 'content'])->name('installer.content');
Route::post('/installer/contact', [WebInstallerController::class, 'sendMessage'])->name('installer.contact');

// Third website (EV Charging Solution). Same set of routes as /installer and
// the same views -- only the website id and the prefix differ, so the two
// sites cannot drift apart.
Route::get('/ev', [WebEvController::class, 'index'])->name('ev.home');
Route::get('/ev/checkout', [WebEvController::class, 'checkout'])->name('ev.checkout');
Route::post('/ev/checkout/confirm', [WebEvController::class, 'confirm'])->name('ev.checkout.confirm');
Route::post('/ev/checkout/place', [WebEvController::class, 'place'])->name('ev.checkout.place');
Route::get('/ev/checkout/done', [WebEvController::class, 'done'])->name('ev.checkout.done');
Route::get('/ev/orders', [WebEvController::class, 'orders'])->name('ev.orders');
Route::post('/ev/orders', [WebEvController::class, 'ordersLookup'])->name('ev.orders.lookup');
Route::get('/ev/receipt/{token}', [WebEvController::class, 'receipt'])->name('ev.receipt');
Route::post('/ev/receipt/{token}/upload', [WebEvController::class, 'uploadAttachment'])->name('ev.receipt.upload');
// Saved cart for guests (keyed on the cart_token cookie, no login)
Route::get('/ev/cart', [WebEvController::class, 'cartShow'])->name('ev.cart.show');
Route::post('/ev/cart', [WebEvController::class, 'cartSave'])->name('ev.cart.save');
Route::delete('/ev/cart', [WebEvController::class, 'cartClear'])->name('ev.cart.clear');
// Checkout address cascade + fare (public)
Route::get('/ev/regions/cities/{provinceCode}', [WebEvController::class, 'regionCities'])->name('ev.regions.cities');
Route::get('/ev/regions/districts/{cityCode}', [WebEvController::class, 'regionDistricts'])->name('ev.regions.districts');
Route::get('/ev/regions/subdistricts/{districtCode}', [WebEvController::class, 'regionSubdistricts'])->name('ev.regions.subdistricts');
Route::get('/ev/fare', [WebEvController::class, 'deliveryFare'])->name('ev.fare');
Route::get('/ev/content/{id}', [WebEvController::class, 'content'])->name('ev.content');
Route::post('/ev/contact', [WebEvController::class, 'sendMessage'])->name('ev.contact');



Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Change own password (admin-styled)
    Route::get('/change-password', [ChangePasswordController::class, 'edit'])->name('password.change');
    Route::put('/change-password', [ChangePasswordController::class, 'update'])->name('password.change.update');
    Route::prefix('master')->group(function () {

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.user.index');
            Route::get('/add', [UserController::class, 'add'])->name('admin.user.add');
            Route::post('/', [UserController::class, 'store'])->name('admin.user.store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('admin.user.update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
        });

        Route::prefix('groupmenu')->group(function () {
            Route::get('/', [GroupMenuController::class, 'index'])->name('admin.groupmenu.index');
            Route::get('/add', [GroupMenuController::class, 'add'])->name('admin.groupmenu.add');
            Route::post('/', [GroupMenuController::class, 'store'])->name('admin.groupmenu.store');
            Route::get('/{id}/edit', [GroupMenuController::class, 'edit'])->name('admin.groupmenu.edit');
            Route::put('/{id}', [GroupMenuController::class, 'update'])->name('admin.groupmenu.update');
            Route::delete('/{id}', [GroupMenuController::class, 'destroy'])->name('admin.groupmenu.destroy');
        });

        Route::prefix('service')->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->name('admin.service.index');
            Route::get('/add', [ServiceController::class, 'add'])->name('admin.service.add');
            Route::post('/', [ServiceController::class, 'store'])->name('admin.service.store');
            Route::get('/{id}/edit', [ServiceController::class, 'edit'])->name('admin.service.edit');
            Route::put('/{id}', [ServiceController::class, 'update'])->name('admin.service.update');
            Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('admin.service.destroy');
        });

        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('admin.categories.index');
            Route::get('/add', [CategoryController::class, 'add'])->name('admin.categories.add');
            Route::post('/', [CategoryController::class, 'store'])->name('admin.categories.store');
            Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        });

        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('admin.product.index');
            Route::get('/add', [ProductController::class, 'add'])->name('admin.product.add');
            Route::post('/', [ProductController::class, 'store'])->name('admin.product.store');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('admin.product.edit');
            Route::put('/{id}', [ProductController::class, 'update'])->name('admin.product.update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('admin.product.destroy');
        });

        Route::prefix('package')->group(function () {
            Route::get('/', [PackageController::class, 'index'])->name('admin.package.index');
            Route::get('/add', [PackageController::class, 'add'])->name('admin.package.add');
            Route::post('/', [PackageController::class, 'store'])->name('admin.package.store');
            Route::get('/{id}/edit', [PackageController::class, 'edit'])->name('admin.package.edit');
            Route::put('/{id}', [PackageController::class, 'update'])->name('admin.package.update');
            Route::delete('/{id}', [PackageController::class, 'destroy'])->name('admin.package.destroy');
        });

        Route::prefix('deliveryprice')->group(function () {
            Route::get('/', [DeliveryPriceController::class, 'index'])->name('admin.deliveryprice.index');
            Route::get('/add', [DeliveryPriceController::class, 'add'])->name('admin.deliveryprice.add');
            Route::post('/', [DeliveryPriceController::class, 'store'])->name('admin.deliveryprice.store');
            // Cascading-select lookups (literal segments, declared before {id}).
            Route::get('/cities/{provinceCode}', [DeliveryPriceController::class, 'cities'])->name('admin.deliveryprice.cities');
            Route::get('/districts/{cityCode}', [DeliveryPriceController::class, 'districts'])->name('admin.deliveryprice.districts');
            Route::get('/subdistricts/{districtCode}', [DeliveryPriceController::class, 'subdistricts'])->name('admin.deliveryprice.subdistricts');
            Route::get('/{id}/edit', [DeliveryPriceController::class, 'edit'])->name('admin.deliveryprice.edit');
            Route::put('/{id}', [DeliveryPriceController::class, 'update'])->name('admin.deliveryprice.update');
            Route::delete('/{id}', [DeliveryPriceController::class, 'destroy'])->name('admin.deliveryprice.destroy');
        });

        Route::prefix('othercharge')->group(function () {
            Route::get('/', [OtherChargeController::class, 'index'])->name('admin.othercharge.index');
            Route::get('/add', [OtherChargeController::class, 'add'])->name('admin.othercharge.add');
            Route::post('/', [OtherChargeController::class, 'store'])->name('admin.othercharge.store');
            Route::get('/{id}/edit', [OtherChargeController::class, 'edit'])->name('admin.othercharge.edit');
            Route::put('/{id}', [OtherChargeController::class, 'update'])->name('admin.othercharge.update');
            Route::delete('/{id}', [OtherChargeController::class, 'destroy'])->name('admin.othercharge.destroy');
        });

        Route::prefix('bank')->group(function () {
            Route::get('/', [BankController::class, 'index'])->name('admin.bank.index');
            Route::get('/add', [BankController::class, 'add'])->name('admin.bank.add');
            Route::post('/', [BankController::class, 'store'])->name('admin.bank.store');
            Route::get('/{id}/edit', [BankController::class, 'edit'])->name('admin.bank.edit');
            Route::put('/{id}', [BankController::class, 'update'])->name('admin.bank.update');
            Route::delete('/{id}', [BankController::class, 'destroy'])->name('admin.bank.destroy');
        });

        Route::prefix('transaction')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('admin.transaction.index');
            Route::get('/{id}', [TransactionController::class, 'show'])->name('admin.transaction.show');
            Route::put('/{id}/status', [TransactionController::class, 'updateStatus'])->name('admin.transaction.status');
        });
    });

    Route::prefix('website')->group(function () {

        Route::prefix('style')->group(function () {
            Route::get('/', [WebStyleController::class, 'index'])->name('admin.webstyle.index');
            Route::get('/{id}/edit', [WebStyleController::class, 'edit'])->name('admin.webstyle.edit');
            Route::put('/{id}', [WebStyleController::class, 'update'])->name('admin.webstyle.update');
        });

        Route::prefix('setting')->group(function () {
            Route::get('/', [WebsiteController::class, 'edit'])->name('admin.website.edit');
            Route::put('/', [WebsiteController::class, 'update'])->name('admin.website.update');
        });

        Route::prefix('banner')->group(function () {
            Route::get('/', [BannerController::class, 'index'])->name('admin.banner.index');
            Route::get('/add', [BannerController::class, 'add'])->name('admin.banner.add');
            Route::post('/', [BannerController::class, 'store'])->name('admin.banner.store');
            Route::get('/{id}/edit', [BannerController::class, 'edit'])->name('admin.banner.edit');
            Route::put('/{id}', [BannerController::class, 'update'])->name('admin.banner.update');
            Route::delete('/{id}', [BannerController::class, 'destroy'])->name('admin.banner.destroy');
        });

        // Landing-page sections: order + visibility only, no create/delete.
        Route::prefix('section')->group(function () {
            Route::get('/', [WebSectionController::class, 'index'])->name('admin.section.index');
            Route::post('/order', [WebSectionController::class, 'saveOrder'])->name('admin.section.order');
            Route::get('/{id}/edit', [WebSectionController::class, 'edit'])->name('admin.section.edit');
            Route::put('/{id}', [WebSectionController::class, 'update'])->name('admin.section.update');
        });

        Route::prefix('about')->group(function () {
            Route::get('/', [AboutController::class, 'index'])->name('admin.about.index');
            Route::get('/add', [AboutController::class, 'add'])->name('admin.about.add');
            Route::post('/', [AboutController::class, 'store'])->name('admin.about.store');
            Route::get('/{id}/edit', [AboutController::class, 'edit'])->name('admin.about.edit');
            Route::put('/{id}', [AboutController::class, 'update'])->name('admin.about.update');
            Route::delete('/{id}', [AboutController::class, 'destroy'])->name('admin.about.destroy');
        });

        Route::prefix('content')->group(function () {
            Route::get('/', [ContentController::class, 'index'])->name('admin.content.index');
            Route::get('/add', [ContentController::class, 'add'])->name('admin.content.add');
            Route::post('/', [ContentController::class, 'store'])->name('admin.content.store');
            Route::get('/{id}/edit', [ContentController::class, 'edit'])->name('admin.content.edit');
            Route::put('/{id}', [ContentController::class, 'update'])->name('admin.content.update');
            Route::delete('/{id}', [ContentController::class, 'destroy'])->name('admin.content.destroy');
        });
    });

    // Inbox ("Pesan Masuk") — read-only.
    Route::prefix('message')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('admin.message.index');
        Route::get('/{id}', [MessageController::class, 'show'])->name('admin.message.show');
    });
});

require __DIR__.'/auth.php';
