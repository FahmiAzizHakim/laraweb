<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Hero slider images per website, snapshotted from the live database.
     * banner_title is globally unique, so it is a safe key to upsert on -- a
     * re-seed will not duplicate banners or discard ones added in the admin.
     *
     * Rows pointing at uploads/banner/... need their files: copy
     * public/uploads/banner/ along with the database.
     *
     * @return void
     */
    public function run()
    {
        $banners = [
            1 => [
                [
                    'banner_title' => 'Hero 1',
                    'banner_img'   => 'webassets/img/gls/hero1.jpg',
                    'banner_text'  => null,
                    'is_active'    => true,
                ],
                [
                    'banner_title' => 'Hero 2',
                    'banner_img'   => 'webassets/img/gls/hero2.jpeg',
                    'banner_text'  => null,
                    'is_active'    => true,
                ],
                [
                    'banner_title' => 'Hero 3',
                    'banner_img'   => 'webassets/img/gls/hero3.jpg',
                    'banner_text'  => null,
                    'is_active'    => true,
                ],
                [
                    'banner_title' => 'Hero 4',
                    'banner_img'   => 'webassets/img/gls/hero4.jpg',
                    'banner_text'  => null,
                    'is_active'    => true,
                ],
                [
                    'banner_title' => 'Hero 5',
                    'banner_img'   => 'webassets/img/gls/hero5.jpg',
                    'banner_text'  => null,
                    'is_active'    => true,
                ],
            ],
            2 => [
                [
                    'banner_title' => 'Instalasi Hero 1',
                    'banner_img'   => 'webassets/img/gls/hero3.jpg',
                    'banner_text'  => null,
                    'is_active'    => false,
                ],
                [
                    'banner_title' => 'Instalasi Hero 2',
                    'banner_img'   => 'webassets/img/gls/hero1.jpg',
                    'banner_text'  => null,
                    'is_active'    => false,
                ],
                [
                    'banner_title' => 'Instalasi Hero 3',
                    'banner_img'   => 'webassets/img/gls/hero5.jpg',
                    'banner_text'  => null,
                    'is_active'    => false,
                ],
                [
                    'banner_title' => 'Meeting Rooms',
                    'banner_img'   => 'uploads/banner/meeting-rooms-20260824015308-rbz97v.jpeg',
                    'banner_text'  => null,
                    'is_active'    => true,
                ],
                [
                    'banner_title' => 'Mgr Room',
                    'banner_img'   => 'uploads/banner/mgr-rooms-20260824015341-KMFou9.png',
                    'banner_text'  => null,
                    'is_active'    => true,
                ],
                [
                    'banner_title' => 'Cleaning Sofa',
                    'banner_img'   => 'uploads/banner/f14710fffcaf6740b450afca4e20a754-20260824015411-Zdwufq.jpg',
                    'banner_text'  => null,
                    'is_active'    => true,
                ],
            ],
            3 => [
                [
                    'banner_title' => 'EV Charging Hero 1',
                    'banner_img'   => 'webassets/img/gls/hero5.jpg',
                    'banner_text'  => null,
                    'is_active'    => false,
                ],
                [
                    'banner_title' => 'EV Charging Hero 2',
                    'banner_img'   => 'webassets/img/gls/hero3.jpg',
                    'banner_text'  => null,
                    'is_active'    => false,
                ],
                [
                    'banner_title' => 'EV Charging Hero 3',
                    'banner_img'   => 'webassets/img/gls/hero1.jpg',
                    'banner_text'  => null,
                    'is_active'    => false,
                ],
                [
                    'banner_title' => 'EV',
                    'banner_img'   => 'uploads/banner/shutterstock-2264639631-1jpg-20260824021306-AqG8FX.jpeg',
                    'banner_text'  => null,
                    'is_active'    => true,
                ],
                [
                    'banner_title' => 'EV2',
                    'banner_img'   => 'uploads/banner/7a56b16d5a0ec3eb1816655d6e731a5c-20260824021317-Ljal6r.jpg',
                    'banner_text'  => null,
                    'is_active'    => true,
                ],
            ],
        ];
        $missing = 0;

        foreach ($banners as $websiteId => $rows) {
            foreach ($rows as $row) {
                DB::table('banners')->updateOrInsert(
                    ['banner_title' => $row['banner_title']],
                    $row + ['website_id' => $websiteId]
                );

                if ($row['banner_img'] && !file_exists(public_path($row['banner_img']))) {
                    $missing++;
                }
            }

            $active = count(array_filter(array_column($rows, 'is_active')));
            $this->command->info("  website $websiteId banners: " . count($rows) . " ($active active)");
        }

        if ($missing) {
            $this->command->warn("  $missing banner file(s) not found under public/ -- copy public/uploads/banner/");
        }
    }
}
