<?php

namespace App\UseCases;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\UseCases\UpdateCourseUseCaseInterface;
use App\Dtos\CourseDto;
use App\Models\Course;

class UpdateCourseUseCase implements UpdateCourseUseCaseInterface
{
    private CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function execute(Course $course, CourseDto $dto): Course
    {
        return $this->courseRepository->updateCourse($course, $dto);
    }
}