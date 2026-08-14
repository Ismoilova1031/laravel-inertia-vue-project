<?php

namespace App\Contracts\Repositories;

interface CourseRepositoryInterface
{
    public function getCourses(): array;
}