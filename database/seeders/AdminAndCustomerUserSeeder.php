<?php

namespace Database\Seeders;

use App\Enums\MembershipRole;
use App\Enums\UserType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAndCustomerUserSeeder extends Seeder
{
    public function run(): void
    {
        $this->upsertUser(
            email: 'a@a.com',
            name: 'Admin User',
            password: '236330',
            type: UserType::Admin,
        );

        $customerUser = $this->upsertUser(
            email: 'b@b.com',
            name: 'Customer User',
            password: '236330',
            type: UserType::Customer,
        );

        $customerCompany = Company::query()->firstOrCreate(
            ['slug' => 'b-customer-company'],
            [
                'name' => 'B Customer Company',
                'is_active' => true,
            ],
        );

        $customerCompany->attachMember($customerUser, MembershipRole::Owner);
    }

    private function upsertUser(string $email, string $name, string $password, UserType $type): User
    {
        $user = User::query()->firstOrNew(['email' => $email]);
        $user->name = $name;
        $user->type = $type;
        $user->is_active = true;
        $user->email_verified_at = now();

        if (! filled($user->password) || ! Hash::check($password, (string) $user->password)) {
            $user->password = $password;
        }

        $user->save();

        return $user;
    }
}
