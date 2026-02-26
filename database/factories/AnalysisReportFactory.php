<?php

namespace Database\Factories;

use App\Models\Company;
use App\Support\ReportCatalog;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalysisReportFactory extends Factory
{
    public function definition(): array
    {
        $reportType = fake()->randomElement(ReportCatalog::keys());
        $catalog = ReportCatalog::get($reportType);
        $primaryYear = fake()->numberBetween(2022, 2026);
        $primaryMonth = fake()->numberBetween(1, 12);
        $hasComparison = fake()->boolean(60);

        return [
            'company_id' => Company::factory(),
            'report_type' => $reportType,
            'report_title' => $catalog['title'],
            'selection_mode' => fake()->randomElement(['preset', 'manual']),
            'window_years' => 1,
            'series_mode' => 'monthly',
            'primary_year' => $primaryYear,
            'primary_month' => $primaryMonth,
            'comparison_year' => $hasComparison ? fake()->numberBetween(2022, 2026) : null,
            'comparison_month' => $hasComparison ? fake()->numberBetween(1, 12) : null,
            'selected_periods' => [sprintf('%d_%02d', $primaryYear, $primaryMonth)],
            'analysis_query' => fake()->optional()->sentence(),
            'content' => "## Örnek Rapor\n\n".fake()->paragraphs(3, true),
            'generated_at' => now()->subDays(fake()->numberBetween(0, 30)),
        ];
    }
}
