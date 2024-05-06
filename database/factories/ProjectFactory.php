<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true), // Generates a random name with 3 words
            'client_id' => Client::factory()->create()->id, // Creates a new client and uses its ID
            'employee_id' => Employee::factory()->create()->id, // Creates a new employee and uses its ID
            'price' => $this->faker->numberBetween(10000, 100000), // Generates a random price between 1000 and 10000
        ];
    }
}
