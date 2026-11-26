<?php

namespace Database\Seeders;

use App\Support\SiteSettings;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(SiteSettings::class)->putMany(SiteSettings::defaults());
    }
}
