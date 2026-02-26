<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'a@a.com');
        $password = env('ADMIN_PASSWORD', '236330');
        $name = env('ADMIN_NAME', 'OpenPDF Admin');

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'is_admin' => true,
                'locale' => 'tr',
                'email_verified_at' => now(),
            ]
        );
    }
}
