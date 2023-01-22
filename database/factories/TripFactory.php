<?php

namespace Database\Factories;

use App\Models\Trip;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Trip::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'frequent' => $this->faker->text(255),
            'type' => 'short',
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'distance' => $this->faker->randomFloat(2, 0, 9999),
            'active' => $this->faker->boolean,
            'start_at' => $this->faker->time,
            'end_at' => $this->faker->time,
            'cron_experations' => $this->faker->text(255),
            'destination_id' => \App\Models\Station::factory(),
            'bus_id' => \App\Models\Bus::factory(),
            'pickup_id' => \App\Models\Station::factory(),
        ];
    }
}
