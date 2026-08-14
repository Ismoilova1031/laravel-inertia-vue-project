<?php

namespace App\UseCases;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\UseCases\GetCoursesUseCaseInterface;

class GetCoursesUseCase implements GetCoursesUseCaseInterface
{
    private CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function execute(): array
    {
        return $this->courseRepository->getCourses();
    }
}