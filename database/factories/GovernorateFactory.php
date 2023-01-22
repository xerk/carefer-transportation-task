<?php

namespace Database\Factories;

use App\Models\Governorate;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class GovernorateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Governorate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'key' => Str::slug($this->faker->name),
        ];
    }
}
