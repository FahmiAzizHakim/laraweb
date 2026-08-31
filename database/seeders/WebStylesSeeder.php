<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WebStylesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Extracts the recurring theme values from the original
     * resources/views/layout/website/css.blade.php so the website
     * styling can be edited from the database.
     *
     * @return void
     */
    public function run()
    {
        // Keyed on (website_id, style_key) instead of wiping the table, so a
        // re-seed tops up missing keys and leaves ids -- and anything edited in
        // the admin -- in place.

        $data1 = array(
            // ---------- Colors ----------
            [
                'style_key'   => 'brand_color',
                'style_label' => 'Brand Color',
                'style_value' => '#40c057',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Primary green used across buttons, cards, tables and icons.',
                'order'       => 1,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'brand_hover',
                'style_label' => 'Brand Hover Color',
                'style_value' => '#48c960',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Hover state for primary green buttons.',
                'order'       => 2,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'accent_color',
                'style_label' => 'Accent Color',
                'style_value' => '#18a3c2',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Teal accent used for main-color text and navbar text-shadow.',
                'order'       => 3,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'second_color',
                'style_label' => 'Second Color',
                'style_value' => '#00f513',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Secondary highlight color (.second-color).',
                'order'       => 4,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'third_color',
                'style_label' => 'Third Color',
                'style_value' => '#1cb79a',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Tertiary highlight color (.third-color).',
                'order'       => 5,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'muted_color',
                'style_label' => 'Muted Text Color',
                'style_value' => '#adadad',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Muted grey for subtitles in testimonials and track items.',
                'order'       => 6,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'text_dark',
                'style_label' => 'Dark Text Color',
                'style_value' => '#000000',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Default dark text color used in headings.',
                'order'       => 7,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'light_color',
                'style_label' => 'Light Text Color',
                'style_value' => '#ffffff',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Light/white text used on the navbar and colored cards.',
                'order'       => 8,
                'is_active'   => true,
            ],

            // ---------- Gradients ----------
            [
                'style_key'   => 'header_gradient',
                'style_label' => 'Header Gradient',
                'style_value' => 'linear-gradient(90deg, rgb(0 199 39) 0%, rgb(0 214 55), rgb(0 152 215) 100%)',
                'style_type'  => 'gradient',
                'style_group' => 'Colors',
                'description' => 'Background gradient for the site #header.',
                'order'       => 20,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'button_gradient',
                'style_label' => 'Button Gradient',
                'style_value' => 'linear-gradient(90deg, rgb(0 233 46) 0%, rgb(0 152 215) 100%)',
                'style_type'  => 'gradient',
                'style_group' => 'Colors',
                'description' => 'Gradient for the tracking custom input-group button.',
                'order'       => 21,
                'is_active'   => true,
            ],

            // ---------- Layout ----------
            [
                'style_key'   => 'container_width',
                'style_label' => 'Container Max Width',
                'style_value' => '1250px',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Max width of the main .container.',
                'order'       => 40,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'banner_height',
                'style_label' => 'Banner Height',
                'style_value' => '300px',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Banner icon height on desktop.',
                'order'       => 41,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'banner_height_md',
                'style_label' => 'Banner Height (Tablet)',
                'style_value' => '225px',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Banner icon height up to 767px wide.',
                'order'       => 42,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'banner_height_sm',
                'style_label' => 'Banner Height (Mobile)',
                'style_value' => '170px',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Banner icon height up to 450px wide.',
                'order'       => 43,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'hero_height',
                'style_label' => 'Hero Height',
                'style_value' => '67vh',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Height of the #hero section.',
                'order'       => 44,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'hero_slider_height',
                'style_label' => 'Hero Slider Height',
                'style_value' => '76vh',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Height of the #hero .hero-slider.',
                'order'       => 45,
                'is_active'   => true,
            ],
        );

        $this->seedFor(1, $data1);

        $data2 = array(
            // ---------- Colors ----------
            [
                'style_key'   => 'brand_color',
                'style_label' => 'Brand Color',
                'style_value' => '#40c057',
                // 'style_value' => '#0f5008',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Primary green used across buttons, cards, tables and icons.',
                'order'       => 1,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'brand_hover',
                'style_label' => 'Brand Hover Color',
                'style_value' => '#48c960',
                // 'style_value' => '#1e8b25',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Hover state for primary green buttons.',
                'order'       => 2,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'accent_color',
                'style_label' => 'Accent Color',
                'style_value' => '#18a3c2',
                // 'style_value' => '#DAA521',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Teal accent used for main-color text and navbar text-shadow.',
                'order'       => 3,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'second_color',
                'style_label' => 'Second Color',
                'style_value' => '#00f513',
                // 'style_value' => '#0f5008',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Secondary highlight color (.second-color).',
                'order'       => 4,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'third_color',
                'style_label' => 'Third Color',
                'style_value' => '#1cb79a',
                // 'style_value' => '#1e8b25',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Tertiary highlight color (.third-color).',
                'order'       => 5,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'muted_color',
                'style_label' => 'Muted Text Color',
                'style_value' => '#adadad',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Muted grey for subtitles in testimonials and track items.',
                'order'       => 6,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'text_dark',
                'style_label' => 'Dark Text Color',
                'style_value' => '#000000',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Default dark text color used in headings.',
                'order'       => 7,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'light_color',
                'style_label' => 'Light Text Color',
                'style_value' => '#ffffff',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Light/white text used on the navbar and colored cards.',
                'order'       => 8,
                'is_active'   => true,
            ],

            // ---------- Gradients ----------
            [
                'style_key'   => 'topbar_color',
                'style_label' => 'Topbar Color',
                'style_value' => '#ffffff',
                // 'style_value' => 'linear-gradient(90deg, #A67C00 0%, #FFBF00 50%, #A67C00 100%)',
                // 'style_type'  => 'text',
                'style_type'  => 'color',
                'style_group' => 'Colors',
                'description' => 'Background color for the site #topbar.',
                'order'       => 20,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'header_color',
                'style_label' => 'Header Color',
                // 'style_value' => '#063B00',
                // 'style_type'  => 'color',
                'style_value' => 'linear-gradient(90deg, rgb(0 199 39) 0%, rgb(0 214 55), rgb(0 152 215) 100%)',
                'style_type'  => 'gradient',
                'style_group' => 'Colors',
                'description' => 'Background color for the site #header.',
                'order'       => 20,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'banner_highlight',
                'style_label' => 'Banner Highlight',
                'style_value' => 'rgb(43 65 32 / 47%)',
                'style_type'  => 'text',
                'style_group' => 'Colors',
                'description' => 'Background color for the site #header.',
                'order'       => 20,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'button_gradient',
                'style_label' => 'Button Gradient',
                'style_value' => 'linear-gradient(90deg, rgb(0 199 39) 0%, rgb(0 214 55), rgb(0 152 215) 100%)',
                // 'style_value' => 'linear-gradient(90deg, #A67C00 0%, #FFBF00 50%, #A67C00 100%)',
                'style_type'  => 'text',
                'style_group' => 'Colors',
                'description' => 'Gradient for the tracking custom input-group button.',
                'order'       => 21,
                'is_active'   => true,
            ],

            // ---------- Layout ----------
            [
                'style_key'   => 'container_width',
                'style_label' => 'Container Max Width',
                'style_value' => '1450px',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Max width of the main .container.',
                'order'       => 40,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'banner_height',
                'style_label' => 'Banner Height',
                'style_value' => '200px',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Banner icon height on desktop.',
                'order'       => 41,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'banner_height_md',
                'style_label' => 'Banner Height (Tablet)',
                'style_value' => '200px',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Banner icon height up to 767px wide.',
                'order'       => 42,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'banner_height_sm',
                'style_label' => 'Banner Height (Mobile)',
                'style_value' => '120px',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Banner icon height up to 450px wide.',
                'order'       => 43,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'hero_height',
                'style_label' => 'Hero Height',
                'style_value' => '100vh',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Height of the #hero section.',
                'order'       => 44,
                'is_active'   => true,
            ],
            [
                'style_key'   => 'hero_slider_height',
                'style_label' => 'Hero Slider Height',
                'style_value' => '100vh',
                'style_type'  => 'size',
                'style_group' => 'Layout',
                'description' => 'Height of the #hero .hero-slider.',
                'order'       => 45,
                'is_active'   => true,
            ],
        );
        $this->seedFor(2, $data2);

        // Website 2 branding: green (main) + gold (secondary accent).
        \DB::table('web_styles')
            ->where('website_id', 2)
            ->where('style_key', 'second_color')
            ->update([
                'style_value' => '#C9A227',
                'style_label' => 'Secondary (Gold)',
                'description' => 'Secondary gold accent (prices, icons, section accents).',
            ]);

        // Website 3 (EV Charging Solution) uses website 2's layout, so it
        // starts from the same style keys...
        $this->seedFor(3, $data2);

        // ...with an electric-blue secondary accent instead of gold, so the two
        // sites are recognisably different.
        \DB::table('web_styles')
            ->where('website_id', 3)
            ->where('style_key', 'second_color')
            ->update([
                'style_value' => '#1c7ed6',
                'style_label' => 'Secondary (Electric Blue)',
                'description' => 'Secondary blue accent (prices, icons, section accents).',
            ]);
    }

    /**
     * Upsert one website's style rows, keyed on style_key so ids never move.
     */
    private function seedFor(int $websiteId, array $rows): void
    {
        foreach ($rows as $row) {
            \DB::table('web_styles')->updateOrInsert(
                ['website_id' => $websiteId, 'style_key' => $row['style_key']],
                $row
            );
        }

        $this->command->info('  website ' . $websiteId . ' styles: ' . count($rows) . ' key(s)');
    }
}
