<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * The three sites reached from the portal on the main index. Rows are keyed
     * on their id and updated in place, so re-seeding never renumbers a website
     * and never orphans the services, products, styles, banners, menus or
     * transactions that point at one.
     *
     * Snapshot of the live database.
     *
     * @return void
     */
    public function run()
    {
        $websites = [
            [
                'id'             => 1,
                'web_name'       => 'GLS Contract Logistic',
                'company_name'   => 'PT. Green Logistic Solution',
                'logo'           => 'webassets/img/gls/gls-bold.png',
                'logo_white'     => 'webassets/img/gls/logo-text-w.png',
                'phone_number'   => '+6221 2919 2608',
                'whatsapp_no'    => null,
                'email'          => 'cargo@gls-logistics.co',
                'facebook_link'  => null,
                'twitter_link'   => null,
                'instagram_link' => null,
                'linkedin_link'  => null,
                'address'        => 'Jl. Laut Arafuru Blok AS No. 7 RT 12/RW 11 Pd. Bambu, Kec. Duren Sawit, Jakarta Timur',
                'location'       => '-6.239005,106.907119',
                'domain'         => null,
                'is_active'      => true,
            ],
            [
                'id'             => 2,
                'web_name'       => 'Furniture/AC Installation and Cleaning',
                'company_name'   => 'PT Teknologi Energi Hijau',
                'logo'           => 'webassets/img/gls/gls-bold.png',
                'logo_white'     => 'webassets/img/gls/logo-text-w.png',
                'phone_number'   => '+6221 2919 2608',
                'whatsapp_no'    => '+6272125746546',
                'email'          => 'cargo@gls-logistics.co',
                'facebook_link'  => null,
                'twitter_link'   => null,
                'instagram_link' => null,
                'linkedin_link'  => null,
                'address'        => 'Jl. Laut Arafuru Blok AS No. 7 RT 12/RW 11 Pd. Bambu, Kec. Duren Sawit, Jakarta Timur',
                'location'       => '-6.239005,106.907119',
                'domain'         => null,
                'is_active'      => true,
            ],
            [
                'id'             => 3,
                'web_name'       => 'EV Charging Solution',
                'company_name'   => 'PT Teknologi Energi Hijau',
                'logo'           => 'webassets/img/gls/gls-bold.png',
                'logo_white'     => 'webassets/img/gls/logo-text-w.png',
                'phone_number'   => '+6221 2919 2608',
                'whatsapp_no'    => '+6272125746546',
                'email'          => 'cargo@gls-logistics.co',
                'facebook_link'  => null,
                'twitter_link'   => null,
                'instagram_link' => null,
                'linkedin_link'  => null,
                'address'        => 'Jl. Laut Arafuru Blok AS No. 7 RT 12/RW 11 Pd. Bambu, Kec. Duren Sawit, Jakarta Timur',
                'location'       => '-6.239005,106.907119',
                'domain'         => null,
                'is_active'      => true,
            ],
        ];

        foreach ($websites as $row) {
            $id = $row['id'];
            unset($row['id']);

            DB::table('websites')->updateOrInsert(['id' => $id], $row);
        }

        $this->command->info('  websites: ' . count($websites));
    }
}
