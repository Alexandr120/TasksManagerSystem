<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
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
            'title' => 'Task title - "'. current(fake()->sentences()) .'"',
            'status' => rand(TaskStatus::PENDING(), TaskStatus::COMPLETED()),
            'description' =>  'Some task description...'
        ];
    }
}
