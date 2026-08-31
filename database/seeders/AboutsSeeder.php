<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * The blocks of each site's About section, snapshotted from the live
     * database. They render in `order`, so the first row is the site's main
     * block -- the text that used to be hard-coded in the Blade view.
     *
     * Keyed on (website_id, about_title) so a re-seed refreshes the seeded
     * blocks and leaves anything written in the admin alone.
     *
     * @return void
     */
    public function run()
    {
        $abouts = [
            1 => [
                [
                    'about_title'    => 'GLS Indonesia',
                    'about_subtitle' => 'End to End Logistics Provider',
                    'about_content'  => 'GLS Indonesia is End to End Logistics Provider Company affiliated with Baruna Power Line Indonesia shipping company who is focusing in Marine Logistics. Under the brand GLS, we champion in Import/Export, Warehouse Management, Fulfilment Center for B2C and B2B, Cargo, Transportation Management for Corporate and Online sellers.

Founded in 2024, GLS offers unique services for B2B and B2C Warehouse and Transport Management.

Armed with 3 Warehouses, 50+ trucking (all type), and 100+ trained WH Staff and Drivers, GLS is ready to be your trusted and most reliable Logistics Partner.',
                    'about_image'    => 'webassets/img/gls/close-up-delivery-man-holding-badge.jpg',
                    'order'          => 0,
                    'is_active'      => true,
                ],
            ],
            2 => [
                [
                    'about_title'    => 'Tentang Layanan Kami',
                    'about_subtitle' => 'Furniture & AC Installation and Cleaning',
                    'about_content'  => 'Kami mengerjakan pemasangan, pembongkaran dan pembersihan furniture serta AC untuk rumah maupun kantor. Setiap pekerjaan ditangani teknisi berpengalaman dengan peralatan lengkap.

Harga mengikuti ukuran pekerjaan, sehingga Anda hanya membayar sesuai kebutuhan. Semua pengerjaan bergaransi dan dijadwalkan tepat waktu.

Hubungi kami untuk konsultasi dan penawaran sesuai kebutuhan Anda.',
                    'about_image'    => 'webassets/img/gls/close-up-hands-carrying-box.jpg',
                    'order'          => 0,
                    'is_active'      => true,
                ],
            ],
            3 => [
                [
                    'about_title'    => 'Tentang EV Charging Solution',
                    'about_subtitle' => 'Home charging & SPKLU',
                    'about_content'  => 'Kami menyediakan dan memasang perangkat pengisian daya kendaraan listrik, dari home charging 7 KW dan 11 KW hingga SPKLU untuk area publik dan komersial.

Seluruh instalasi dikerjakan sesuai standar kelistrikan oleh teknisi bersertifikat, termasuk survei lokasi sebelum pemasangan.

Setiap unit disertai garansi barang dan garansi pemasangan, serta konsultasi produk sebelum Anda memutuskan.',
                    'about_image'    => 'webassets/img/gls/close-up-delivery-man-with-tablet.jpg',
                    'order'          => 0,
                    'is_active'      => true,
                ],
            ],
        ];

        foreach ($abouts as $websiteId => $rows) {
            foreach ($rows as $row) {
                DB::table('abouts')->updateOrInsert(
                    ['website_id' => $websiteId, 'about_title' => $row['about_title']],
                    $row
                );
            }

            $this->command->info("  website $websiteId abouts: " . implode(', ', array_column($rows, 'about_title')));
        }
    }
}
