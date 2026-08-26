<?php

namespace App\UseCases;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\UseCases\UpdateCourseUseCaseInterface;
use App\Dtos\CourseDto;
use App\Models\Course;

class UpdateCourseUseCase implements UpdateCourseUseCaseInterface
{

    public function __construct(
        private CourseRepositoryInterface $courseRepository
    ){}

    public function execute(Course $course, CourseDto $dto): Course
    {
        return $this->courseRepository->updateCourse($course, $dto);
    }
}