<?php

namespace App\UseCases;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\UseCases\GetCoursesUseCaseInterface;
use Illuminate\Support\Collection;

class GetCoursesUseCase implements GetCoursesUseCaseInterface
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository
    ) {}

    public function execute(): Collection
    {
        return $this->courseRepository->getCourses();
    }
}
