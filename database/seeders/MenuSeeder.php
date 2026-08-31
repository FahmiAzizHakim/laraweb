<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds the full admin menu tree for every website in the websites table,
     * so a new website (3, EV Charging Solution) gets its own sidebar. Parent
     * links are resolved per website via a key map, so each website's children
     * point at that website's own folders.
     *
     * Rows are keyed on (website_id, name_en) and updated in place, so running
     * this again tops up the missing menus instead of duplicating the tree --
     * important because users_menugroupdetail rows point at menu ids.
     *
     * The transaction-side menus (Transactions, Banks, Delivery Prices, Other
     * Charges) stay in the tree on purpose: the shop front no longer sells,
     * but the back office must keep managing what was already sold.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            ['key' => 'dashboard',   'parent' => null,              'name_in' => 'Dashboard',                   'name_en' => 'Dashboard',          'menu_url' => 'dashboard',        'menu_icon' => 'fas fa-table',            'menu_type' => 'MENU'],
            ['key' => 'website',     'parent' => null,              'name_in' => 'Website',                     'name_en' => 'Website',            'menu_url' => '',                 'menu_icon' => 'far fa-window-restore',   'menu_type' => 'FOLDER'],
            ['key' => 'web_setting', 'parent' => 'website',         'name_in' => 'Pengaturan Website',          'name_en' => 'Website Setting',    'menu_url' => 'website/setting',  'menu_icon' => 'fas fa-chalkboard',       'menu_type' => 'MENU'],
            ['key' => 'banner',      'parent' => 'website',         'name_in' => 'Banner',                      'name_en' => 'Banner',             'menu_url' => 'website/banner',   'menu_icon' => 'far fa-images',           'menu_type' => 'MENU'],
            ['key' => 'content',     'parent' => 'website',         'name_in' => 'Konten',                      'name_en' => 'Content',            'menu_url' => 'website/content',  'menu_icon' => 'fas fa-newspaper',        'menu_type' => 'MENU'],
            ['key' => 'style',       'parent' => 'website',         'name_in' => 'Pengaturan Tampilan Utama',   'name_en' => 'Main Style Setting', 'menu_url' => 'website/style',    'menu_icon' => 'fas fa-fill-drip',        'menu_type' => 'MENU'],
            ['key' => 'settings',    'parent' => null,              'name_in' => 'Pengaturan',                  'name_en' => 'Settings',           'menu_url' => '',                 'menu_icon' => 'fas fa-cogs',             'menu_type' => 'FOLDER'],
            ['key' => 'user',        'parent' => 'settings',        'name_in' => 'Pengguna',                    'name_en' => 'Users',              'menu_url' => 'master/user',      'menu_icon' => 'fas fa-user',             'menu_type' => 'MENU'],
            ['key' => 'groupmenu',   'parent' => 'settings',        'name_in' => 'Grup Hak Akses',              'name_en' => 'User Access Group',  'menu_url' => 'master/groupmenu', 'menu_icon' => 'fas fa-users',            'menu_type' => 'MENU'],
            ['key' => 'inbox',       'parent' => null,              'name_in' => 'Pesan Masuk',                 'name_en' => 'Inbox',              'menu_url' => 'message',          'menu_icon' => 'fa fa-envelope-open-text','menu_type' => 'MENU'],
            ['key' => 'transaction', 'parent' => null,              'name_in' => 'Transaksi',                   'name_en' => 'Transactions',       'menu_url' => 'master/transaction','menu_icon' => 'fas fa-receipt',        'menu_type' => 'MENU'],
            ['key' => 'masterdata',  'parent' => null,              'name_in' => 'Master data',                 'name_en' => 'Masterdata',         'menu_url' => '',                 'menu_icon' => 'fas fa-database',         'menu_type' => 'FOLDER'],
            ['key' => 'service',     'parent' => 'masterdata',      'name_in' => 'Layanan',                     'name_en' => 'Services',           'menu_url' => 'master/service',   'menu_icon' => 'fas fa-hand-holding',     'menu_type' => 'MENU'],
            ['key' => 'categories',  'parent' => 'masterdata',      'name_in' => 'Kategori',                    'name_en' => 'Categories',         'menu_url' => 'master/categories','menu_icon' => 'fas fa-th-large',         'menu_type' => 'MENU'],
            ['key' => 'products',    'parent' => 'masterdata',      'name_in' => 'Produk',                      'name_en' => 'Products',           'menu_url' => 'master/product',   'menu_icon' => 'fas fa-gifts',            'menu_type' => 'MENU'],
            ['key' => 'packages',    'parent' => 'masterdata',      'name_in' => 'Paket',                       'name_en' => 'Packages',           'menu_url' => 'master/package',   'menu_icon' => 'fas fa-box-open',         'menu_type' => 'MENU'],
            ['key' => 'deliveryprice','parent' => 'masterdata',     'name_in' => 'Harga Pengiriman',            'name_en' => 'Delivery Prices',    'menu_url' => 'master/deliveryprice','menu_icon' => 'fas fa-truck',         'menu_type' => 'MENU'],
            ['key' => 'othercharge', 'parent' => 'masterdata',      'name_in' => 'Biaya Lainnya',               'name_en' => 'Other Charges',      'menu_url' => 'master/othercharge','menu_icon' => 'fas fa-coins',          'menu_type' => 'MENU'],
            ['key' => 'bank',        'parent' => 'masterdata',      'name_in' => 'Bank',                        'name_en' => 'Banks',              'menu_url' => 'master/bank',      'menu_icon' => 'fas fa-university',       'menu_type' => 'MENU'],
        ];

        $websiteIds = DB::table('websites')->orderBy('id')->pluck('id');

        foreach ($websiteIds as $websiteId) {
            $idMap = []; // key => menu id (per website)
            $added = 0;

            foreach ($menus as $menu) {
                $key = ['website_id' => $websiteId, 'name_en' => $menu['name_en']];

                $existing = DB::table('menus')->where($key)->value('id');

                DB::table('menus')->updateOrInsert($key, [
                    'name_in'   => $menu['name_in'],
                    'parent_id' => $menu['parent'] ? ($idMap[$menu['parent']] ?? null) : null,
                    'menu_url'  => $menu['menu_url'],
                    'menu_icon' => $menu['menu_icon'],
                    'menu_type' => $menu['menu_type'],
                ]);

                if (!$existing) {
                    $added++;
                }

                $idMap[$menu['key']] = DB::table('menus')->where($key)->value('id');
            }

            $this->command->info("  website $websiteId menus: " . count($menus) . " total, $added new");
        }
    }
}
