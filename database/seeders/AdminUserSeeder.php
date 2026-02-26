<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@openpdf.com.tr');
        $password = env('ADMIN_PASSWORD', 'ChangeMe123!');

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'OpenPDF Admin',
                'password' => Hash::make($password),
                'is_admin' => true,
                'locale' => 'tr',
                'email_verified_at' => now(),
            ]
        );
    }
}
