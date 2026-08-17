<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin Account
        User::updateOrCreate(
            ['email' => 'admin@jacario.com'],
            [
                'name' => 'Jawed Hasan',
                'phone' => '+91 98765 43210',
                'role' => 'super_admin',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // 2. Product Lead Account
        User::updateOrCreate(
            ['email' => 'manager@jacario.com'],
            [
                'name' => 'Elena Rostova (Product Lead)',
                'phone' => '+91 98765 43211',
                'role' => 'product_manager',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // 3. Customer Test Account
        User::updateOrCreate(
            ['email' => 'customer@jacario.com'],
            [
                'name' => 'Julian Montgomery',
                'phone' => '+91 98200 12345',
                'role' => 'customer',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
    }
}
