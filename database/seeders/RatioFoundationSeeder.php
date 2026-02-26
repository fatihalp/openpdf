<?php

namespace Database\Seeders;

use App\Models\RatioBenchmark;
use App\Models\RatioDefinition;
use Illuminate\Database\Seeder;

class RatioFoundationSeeder extends Seeder
{
    public function run(): void
    {
        RatioDefinition::syncCatalog();
        RatioBenchmark::syncCatalogDefaults();
    }
}
