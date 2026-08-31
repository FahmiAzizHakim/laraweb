<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SiteStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * The portal and its three websites -- structure, admin access and catalog:
     *
     *   Portal (main index)
     *    |- 1  GLS Kontrak Logistic
     *    |- 2  Furniture/AC Installation and Cleaning
     *    |   |- Furniture       -> Installation (Small/Medium/Large), Cleaning (Small/Medium/Large)
     *    |   |- AC              -> Installation, Cleaning
     *    |- 3  EV Charging Solution
     *        |- Home Charging   -> 7 KW, 11 KW
     *        |- Public Charging / SPKLU -> Product 1, Product 2  (inactive until specified)
     *
     * Packages are listed under their service: Home Charging carries the two
     * 7 KW / 11 KW installation packages.
     *
     * Safe to re-run. The catalog seeders (services, products, variants,
     * packages) do a real reset: they clear these websites' catalog and build
     * it again, so the ids come out clean. Everything else -- websites, menus,
     * access, users, styles, banners, contents -- is upserted in place, so a
     * reset never costs an admin their theme edits or their password.
     *
     * Nothing here touches transactions, banks, delivery prices or carts.
     * Sold packages are snapshotted onto transaction_packages, so resetting
     * the package catalog leaves placed orders and their receipts intact.
     *
     * Run on its own with:
     *   php artisan db:seed --class=SiteStructureSeeder
     *
     * which skips the slow reference data (codes, regions, couriers) that only
     * a fresh install needs.
     *
     * @return void
     */
    public function run()
    {
        $steps = [
            'Websites'          => WebsitesSeeder::class,
            'Admin menus'       => MenuSeeder::class,
            'Access groups'     => UserGroupsSeeder::class,
            'Menu grants'       => UserGroupsDetailSeeder::class,
            'Admin users'       => UsersSeeder::class,
            'Styles'            => WebStylesSeeder::class,
            'Banners'           => BannersSeeder::class,
            'Contents'          => ContentsSeeder::class,
            'Page sections'     => WebSectionsSeeder::class,
            'About sections'    => AboutsSeeder::class,
            'Services'          => ServicesSeeder::class,
            'Products'          => ProductsSeeder::class,
            'Product variants'  => ProductVariantsSeeder::class,
            'Product images'    => ProductImagesSeeder::class,
            'Product specs'     => ProductSpecificationsSeeder::class,
            'Other charges'     => OtherChargesSeeder::class,
            'Packages'          => PackagesSeeder::class,
        ];

        foreach ($steps as $label => $class) {
            $this->command->newLine();
            $this->command->comment($label);
            $this->call($class);
        }
    }
}
