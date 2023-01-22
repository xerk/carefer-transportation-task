<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Adding an admin user
        $user = \App\Models\User::factory()
            ->count(1)
            ->create([
                'email' => 'admin@admin.com',
                'password' => \Hash::make('admin'),
                'type' => 'admin',
            ]);


        $this->call(DiscountSeeder::class);
        $this->call(DriverSeeder::class);
        $this->call(GovernorateSeeder::class);
        // $this->call(TripSeeder::class);
        // $this->call(UserSeeder::class);
    }
}
