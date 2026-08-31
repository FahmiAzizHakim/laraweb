<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Two halves:
     *
     *  - Reference data (codes, sequences, regions, couriers). Slow, and only
     *    a fresh install needs it -- these seeders insert unconditionally.
     *  - SiteStructureSeeder: the portal, its three websites, their admin
     *    access and their catalog. Safe to re-run on a live database, so a
     *    structure change is applied with just:
     *        php artisan db:seed --class=SiteStructureSeeder
     */
    public function run(): void
    {
        // Websites first: the menu, style, banner and catalog seeders all key
        // off the rows it creates.
        $this->call(WebsitesSeeder::class);

        // ---- Reference data (fresh install only) ----
        $this->call(CodesSeeder::class);
        $this->call(MdtSequencesFormatSeeder::class);
        $this->call(RajaongkirmapTableSeeder::class);
        $this->call(GlbCountriesTableSeeder::class);
        $this->call(GlbProvincesTableSeeder::class);
        $this->call(GlbCitiesTableSeeder::class);
        $this->call(GlbDistrictsTableSeeder::class);
        $this->call(CouriersTableSeeder::class);

        // ---- Portal + websites: access, styling and catalog ----
        $this->call(SiteStructureSeeder::class);
    }
}
