<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->email,
            'status' => $this->faker->randomElement(['active','inactive','pending']),
            'total_project' => $this->faker->numberBetween(1, 20),

        ];
    }
}
