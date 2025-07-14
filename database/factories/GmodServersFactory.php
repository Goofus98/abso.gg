<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GmodServersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'api_key' => $this->faker->sentence(3),
        ];
    }
}
