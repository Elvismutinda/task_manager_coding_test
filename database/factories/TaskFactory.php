<?php

namespace Database\Factories;

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
            'user_id' => rand(1, 2),
            'task_name' => fake()->sentence(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed']),
            'deadline' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
