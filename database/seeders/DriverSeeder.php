<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create User type driver, and each driver has bus
        $driver = \App\Models\User::factory()
            ->count(3)
            ->create(['type' => 'driver']);

        // Each Driver has bus and each bus assign seats
        $driver->each(function ($user) {
            $bus = \App\Models\Bus::factory()
                ->count(1)
                ->create(['user_id' => $user->id]);

            $bus->each(function ($bus) {
                $seatFile = fopen(base_path("database/seeders/data/seats.csv"), "r");
                // Import seats from csv file to Seat table
                while (($seatData = fgetcsv($seatFile, 1000, ",")) !== false) {
                    \App\Models\Seat::updateOrCreate(['referance' => $seatData[0]], [
                        'number' => $seatData[1],
                        'line' => $seatData[2],
                    ]);
                }

                // Get all seats from Seat table
                $seats = \App\Models\Seat::all();

                $bus->seats()->attach($seats);
            });
        });
    }
}
