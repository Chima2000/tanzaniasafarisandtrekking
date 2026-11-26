<?php

namespace Database\Seeders;

use App\Support\CatalogSync;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(CatalogSync::class)->syncPackages();
    }
}
