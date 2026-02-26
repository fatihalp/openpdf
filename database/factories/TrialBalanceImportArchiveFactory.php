<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\TrialBalanceImportArchive;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrialBalanceImportArchiveFactory extends Factory
{
    protected $model = TrialBalanceImportArchive::class;

    public function definition(): array
    {
        $source = fake()->randomElement(['isyatirim', 'rota', 'luca']);

        return [
            'company_id' => Company::factory(),
            'reference_year' => fake()->numberBetween(2023, 2026),
            'reference_month' => fake()->numberBetween(1, 12),
            'source' => $source,
            'input_format' => $source === 'isyatirim'
                ? fake()->randomElement(['json', 'csv', 'xls', 'xlsx'])
                : 'xlsx',
            'original_filename' => fake()->word().'.xlsx',
            'storage_disk' => 'local',
            'storage_path' => 'mizan-originals/test/'.fake()->uuid().'.xlsx',
            'file_hash' => fake()->sha1(),
            'file_size' => fake()->numberBetween(512, 10240),
            'raw_rows' => [
                ['header_1', 'header_2'],
                ['row_1', 'row_2'],
            ],
            'normalized_entries' => $source === 'isyatirim'
                ? [[
                    'account_code' => '100',
                    'account_name' => 'Kasa',
                    'account_type' => 'asset',
                    'debit' => 100.0,
                    'credit' => 0.0,
                    'balance' => 100.0,
                    'is_cumulative' => true,
                ]]
                : [],
            'metadata' => [
                'factory' => true,
            ],
            'is_latest' => true,
        ];
    }
}
