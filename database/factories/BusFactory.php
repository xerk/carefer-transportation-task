<?php

namespace Database\Factories;

use App\Models\Bus;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'maintenance' => $this->faker->boolean,
            'capacity' => 20,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
