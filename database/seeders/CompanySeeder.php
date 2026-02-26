<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            'DijiCFO Demo',
            'İş Yatırım Demo',
            'Örnek Üretim A.Ş.',
        ];

        foreach ($companies as $name) {
            $slug = str($name)->slug()->toString();

            Company::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'is_active' => true,
                ]
            );
        }
    }
}
