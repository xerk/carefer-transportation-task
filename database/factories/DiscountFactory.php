<?php

namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Discount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'number_of_seats' => $this->faker->randomNumber(0),
            'percentage' => $this->faker->randomNumber(0),
        ];
    }
}
