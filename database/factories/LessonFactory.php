<?php

namespace Database\Factories;

use App\Enums\LessonType;
use App\Models\Lesson;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_id' => $this->faker->numberBetween(1, 5),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'lesson_type' => LessonType::TEXT,
            'lesson_content' => $this->faker->paragraphs(3, true),
            'sort_order' => $this->faker->numberBetween(1, 1000),
        ];
    }

    public function task(): static
    {
        return $this->state(['lesson_type' => LessonType::TASK, 'lesson_content' => null])
            ->has(Task::factory(), 'task');
    }
}
