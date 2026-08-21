<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SizeAndColorSeeder::class,
            BannerSeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
            CouponSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
