<?php

namespace Database\Factories;

use App\Models\Station;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class StationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Station::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'active' => $this->faker->boolean,
            'city_id' => \App\Models\City::factory(),
        ];
    }
}
