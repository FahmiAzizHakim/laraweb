<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * News & articles per website, snapshotted from the live database. Keyed on
     * content_title (globally unique) rather than wiping the table, so a re-seed
     * keeps ids and anything written in the admin.
     *
     * @return void
     */
    public function run()
    {
        $contents = [
            1 => [
                [
                    'content_title'    => 'Reliable End to End Logistics',
                    'content_subtitle' => 'GLS Indonesia',
                    'description'      => 'Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et.',
                    'content'          => '<p>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et.</p><p>GLS Indonesia is an End to End Logistics Provider Company. Under the brand GLS, we champion in Import/Export, Warehouse Management, Fulfilment Center for B2C and B2B, Cargo, and Transportation Management for Corporate and Online sellers.</p>',
                    'media'            => 'webassets/img/gls/hero1.jpg',
                    'reference'        => null,
                    'additional_url'   => null,
                    'is_active'        => true,
                    'created_at'       => '2024-02-24 10:00:00',
                    'updated_at'       => '2024-02-24 10:00:00',
                ],
                [
                    'content_title'    => 'Warehouse Management Excellence',
                    'content_subtitle' => 'GLS Indonesia',
                    'description'      => 'Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat.',
                    'content'          => '<p>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat.</p><p>GLS Indonesia is an End to End Logistics Provider Company. Under the brand GLS, we champion in Import/Export, Warehouse Management, Fulfilment Center for B2C and B2B, Cargo, and Transportation Management for Corporate and Online sellers.</p>',
                    'media'            => 'webassets/img/gls/hero2.jpeg',
                    'reference'        => null,
                    'additional_url'   => null,
                    'is_active'        => true,
                    'created_at'       => '2024-02-24 10:01:00',
                    'updated_at'       => '2024-02-24 10:01:00',
                ],
                [
                    'content_title'    => 'Cargo & Transportation Updates',
                    'content_subtitle' => 'GLS Indonesia',
                    'description'      => 'Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet.',
                    'content'          => '<p>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet.</p><p>GLS Indonesia is an End to End Logistics Provider Company. Under the brand GLS, we champion in Import/Export, Warehouse Management, Fulfilment Center for B2C and B2B, Cargo, and Transportation Management for Corporate and Online sellers.</p>',
                    'media'            => 'webassets/img/gls/hero3.jpg',
                    'reference'        => null,
                    'additional_url'   => null,
                    'is_active'        => true,
                    'created_at'       => '2024-02-24 10:02:00',
                    'updated_at'       => '2024-02-24 10:02:00',
                ],
                [
                    'content_title'    => 'Fulfilment for B2C and B2B',
                    'content_subtitle' => 'GLS Indonesia',
                    'description'      => 'Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit.',
                    'content'          => '<p>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit.</p><p>GLS Indonesia is an End to End Logistics Provider Company. Under the brand GLS, we champion in Import/Export, Warehouse Management, Fulfilment Center for B2C and B2B, Cargo, and Transportation Management for Corporate and Online sellers.</p>',
                    'media'            => 'webassets/img/gls/hero4.jpg',
                    'reference'        => null,
                    'additional_url'   => null,
                    'is_active'        => true,
                    'created_at'       => '2024-02-24 10:03:00',
                    'updated_at'       => '2024-02-24 10:03:00',
                ],
                [
                    'content_title'    => 'Your Trusted Logistics Partner',
                    'content_subtitle' => 'GLS Indonesia',
                    'description'      => 'Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam.',
                    'content'          => '<p>Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam.</p><p>GLS Indonesia is an End to End Logistics Provider Company. Under the brand GLS, we champion in Import/Export, Warehouse Management, Fulfilment Center for B2C and B2B, Cargo, and Transportation Management for Corporate and Online sellers.</p>',
                    'media'            => 'webassets/img/gls/hero5.jpg',
                    'reference'        => null,
                    'additional_url'   => null,
                    'is_active'        => true,
                    'created_at'       => '2024-02-24 10:04:00',
                    'updated_at'       => '2024-02-24 10:04:00',
                ],
            ],
            2 => [
                [
                    'content_title'    => 'Furniture Installation Done Right',
                    'content_subtitle' => 'Furniture & AC',
                    'description'      => 'Wardrobes, tables, chairs and shelving assembled and dismantled cleanly, priced by size.',
                    'content'          => '<p>Wardrobes, tables, chairs and shelving assembled and dismantled cleanly, priced by size.</p><p>PT Teknologi Energi Hijau handles furniture and AC installation, dismantling and cleaning for homes and offices, carried out by an experienced team with complete equipment and a workmanship guarantee.</p>',
                    'media'            => 'webassets/img/gls/hero3.jpg',
                    'reference'        => null,
                    'additional_url'   => null,
                    'is_active'        => true,
                    'created_at'       => '2024-03-10 10:00:00',
                    'updated_at'       => '2024-03-10 10:00:00',
                ],
                [
                    'content_title'    => 'Furniture Cleaning by Size',
                    'content_subtitle' => 'Furniture & AC',
                    'description'      => 'Deep cleaning for furniture of any size, with the right method for each material.',
                    'content'          => '<p>Deep cleaning for furniture of any size, with the right method for each material.</p><p>PT Teknologi Energi Hijau handles furniture and AC installation, dismantling and cleaning for homes and offices, carried out by an experienced team with complete equipment and a workmanship guarantee.</p>',
                    'media'            => 'webassets/img/gls/hero1.jpg',
                    'reference'        => null,
                    'additional_url'   => null,
                    'is_active'        => true,
                    'created_at'       => '2024-03-10 10:01:00',
                    'updated_at'       => '2024-03-10 10:01:00',
                ],
                [
                    'content_title'    => 'AC Installation and Cleaning',
                    'content_subtitle' => 'Furniture & AC',
                    'description'      => 'New installs, relocation and washing for home and office AC units, handled fast and guaranteed.',
                    'content'          => '<p>New installs, relocation and washing for home and office AC units, handled fast and guaranteed.</p><p>PT Teknologi Energi Hijau handles furniture and AC installation, dismantling and cleaning for homes and offices, carried out by an experienced team with complete equipment and a workmanship guarantee.</p>',
                    'media'            => 'webassets/img/gls/hero5.jpg',
                    'reference'        => null,
                    'additional_url'   => null,
                    'is_active'        => true,
                    'created_at'       => '2024-03-10 10:02:00',
                    'updated_at'       => '2024-03-10 10:02:00',
                ],
            ],
            3 => [
                [
                    'content_title'    => 'Home Charging for Your EV',
                    'content_subtitle' => 'EV Charging Solution',
                    'description'      => 'A 7 KW or 11 KW home charger installed to electrical standards by certified technicians.',
                    'content'          => '<p>A 7 KW or 11 KW home charger installed to electrical standards by certified technicians.</p><p>PT Teknologi Energi Hijau supplies and installs electric vehicle charging, from home charging units to public SPKLU stations, to standards and by certified technicians.</p>',
                    'media'            => 'webassets/img/gls/hero5.jpg',
                    'reference'        => null,
                    'additional_url'   => null,
                    'is_active'        => true,
                    'created_at'       => '2024-04-05 10:00:00',
                    'updated_at'       => '2024-04-05 10:00:00',
                ],
                [
                    'content_title'    => 'Choosing Between 7 KW and 11 KW',
                    'content_subtitle' => 'EV Charging Solution',
                    'description'      => 'How charging speed, your household supply and your car all decide which unit fits you.',
                    'content'          => '<p>How charging speed, your household supply and your car all decide which unit fits you.</p><p>PT Teknologi Energi Hijau supplies and installs electric vehicle charging, from home charging units to public SPKLU stations, to standards and by certified technicians.</p>',
                    'media'            => 'webassets/img/gls/hero3.jpg',
                    'reference'        => null,
                    'additional_url'   => null,
                    'is_active'        => true,
                    'created_at'       => '2024-04-05 10:01:00',
                    'updated_at'       => '2024-04-05 10:01:00',
                ],
                [
                    'content_title'    => 'Public Charging and SPKLU',
                    'content_subtitle' => 'EV Charging Solution',
                    'description'      => 'Charging stations for commercial and public sites, from survey through to commissioning.',
                    'content'          => '<p>Charging stations for commercial and public sites, from survey through to commissioning.</p><p>PT Teknologi Energi Hijau supplies and installs electric vehicle charging, from home charging units to public SPKLU stations, to standards and by certified technicians.</p>',
                    'media'            => 'webassets/img/gls/hero1.jpg',
                    'reference'        => null,
                    'additional_url'   => null,
                    'is_active'        => true,
                    'created_at'       => '2024-04-05 10:02:00',
                    'updated_at'       => '2024-04-05 10:02:00',
                ],
            ],
        ];

        foreach ($contents as $websiteId => $rows) {
            foreach ($rows as $row) {
                DB::table('contents')->updateOrInsert(
                    ['content_title' => $row['content_title']],
                    $row + ['website_id' => $websiteId]
                );
            }

            $this->command->info("  website $websiteId contents: " . count($rows));
        }
    }
}
