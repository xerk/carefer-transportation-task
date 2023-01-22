<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    }

    // Create User type driver, and each driver has bus and each bus has seats with random data
    public function createDrivers()
    {
        $users = factory(\App\Models\User::class, 10)->create();
        $users->each(function ($user) {
            $user->assignRole('driver');
            $bus = factory(\App\Models\Bus::class)->create(['user_id' => $user->id]);
            $seats = factory(\App\Models\Seat::class, 20)->create();
            $bus->seats()->attach($seats);
        });
    }

    // Create User type passenger
    public function createPassengers()
    {
        $users = factory(\App\Models\User::class, 10)->create();
        $users->each(function ($user) {
            $user->assignRole('passenger');
        });
    }

    // Create Buses with random data
    public function createBuses()
    {
        $buses = factory(\App\Models\Bus::class, 10)->create();
    }

    // Create Government with random data and create cities for each government and create stations for each city
    public function createGovernments()
    {
        $governments = factory(\App\Models\Government::class, 10)->create();
        $governments->each(function ($government) {
            $cities = factory(\App\Models\City::class, 10)->create(['government_id' => $government->id]);
            $cities->each(function ($city) {
                $stations = factory(\App\Models\Station::class, 10)->create(['city_id' => $city->id]);
            });
        });
    }
}
