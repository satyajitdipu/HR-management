<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_status' => $this->faker->randomElement(['Completed Tasks', 'Inprogress Tasks', 'On Hold Tasks', 'Pending Tasks', 'Review Tasks']),
            'name' => $this->faker->sentence(), // Generates a random sentence for the task name
            'project_id' => Project::factory(), // Uses a random existing project ID
            'employee_id' => Employee::factory(), // Uses a random existing employee ID
        ];
    }
}
