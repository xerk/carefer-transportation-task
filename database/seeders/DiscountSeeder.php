<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('discounts')->updateOrInsert(
            // Discount 5%
            ['name' => 'Discount 5%',],
            [
                'number_of_seats' => 5,
                'percentage' => 5,
            ]
        );

        \DB::table('discounts')->updateOrInsert(
            // Discount 10%
            ['name' => 'Discount 10%',],
            [
                'number_of_seats' => 10,
                'percentage' => 10,
            ]
        );

        \DB::table('discounts')->updateOrInsert(
            // Discount 15%
            ['name' => 'Discount 15%',],
            [
                'number_of_seats' => 15,
                'percentage' => 15,
            ]
        );

        \DB::table('discounts')->updateOrInsert(
            // Discount 20%
            ['name' => 'Discount 20%',],
            [
                'number_of_seats' => 20,
                'percentage' => 20,
            ]
        );
    }
}
