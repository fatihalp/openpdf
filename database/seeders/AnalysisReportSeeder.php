<?php

namespace Database\Seeders;

use App\Models\AnalysisReport;
use Illuminate\Database\Seeder;

class AnalysisReportSeeder extends Seeder
{
    public function run(): void
    {
        AnalysisReport::factory()->count(10)->create();
    }
}
