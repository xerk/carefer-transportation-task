<?php

namespace Database\Factories;

use App\Models\Seat;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Seat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'referance' => $this->faker->text(255),
            'number' => $this->faker->randomNumber,
            'line' => $this->faker->randomElements(['A', 'B']),
        ];
    }
}
