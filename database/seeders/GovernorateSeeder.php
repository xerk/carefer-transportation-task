<?php

namespace Database\Seeders;

use App\Models\Governorate;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Governorate
        $governorate = Governorate::updateOrCreate(['key' => 'cairo'], [
            'name' => 'Cairo'
        ]);

        // Assign cities to each government
        $governorate->cities()->updateOrCreate(['key' => 'cairo'], [
            'name' => 'Cairo'
        ]);

        // Create station for  city
        $governorate->cities->where('key', 'cairo')->first()->stations()->updateOrCreate(['name' => 'Cairo'], [
            'name' => 'Cairo',
            'latitude' => '30.0444',
            'longitude' => '31.2357',
        ]);

        $governorate = Governorate::updateOrCreate(['key' => 'alexandria'], [
            'name' => 'Alexandria'
        ]);
        $governorate->cities()->updateOrCreate(['key' => 'alexandria'], [
            'name' => 'Alexandria'
        ]);

        // Create station for city
        $governorate->cities->where('key', 'alexandria')->first()->stations()->updateOrCreate(['name' => 'Alexandria'], [
            'name' => 'Alexandria',
            'latitude' => '31.2001',
            'longitude' => '29.9187',
        ]);

        $governorate = Governorate::updateOrCreate(['key' => 'aswan'], [
            'name' => 'Aswan'
        ]);

        $governorate->cities()->updateOrCreate(['key' => 'aswan'], [
            'name' => 'Aswan'
        ]);

        // Create station for city
        $governorate->cities->where('key', 'aswan')->first()->stations()->updateOrCreate(['name' => 'Aswan'], [
            'name' => 'Aswan',
            'latitude' => '24.0934',
            'longitude' => '32.9070',
        ]);
    }
}
