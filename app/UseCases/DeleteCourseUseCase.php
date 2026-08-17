<?php

namespace App\UseCases;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\UseCases\DeleteCourseUseCaseInterface;
use App\Models\Course;

class DeleteCourseUseCase implements DeleteCourseUseCaseInterface
{
    private CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function execute(Course $course): void
    {
        $this->courseRepository->delete($course);
    }
}