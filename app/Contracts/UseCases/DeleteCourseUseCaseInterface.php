<?php

namespace App\Contracts\UseCases;

use App\Models\Course;

interface DeleteCourseUseCaseInterface
{
    public function execute(Course $course): void;
}