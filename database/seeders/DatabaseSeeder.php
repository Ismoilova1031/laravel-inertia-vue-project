<?php

namespace Database\Seeders;

use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\LessonModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        CourseModel::factory(10)->create()->each(function ($course) {
            $course->lessons()->saveMany(LessonModel::factory(5)->make());
        });

    }
}
