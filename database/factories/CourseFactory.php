<?php

namespace Database\Factories;

use App\Infrastructure\Eloquent\CourseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseModel>
 */
class CourseFactory extends Factory
{
    protected $model = CourseModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->numberBetween(1, 5),
            'status' => $this->faker->numberBetween(0, 1),
        ];
    }
}
