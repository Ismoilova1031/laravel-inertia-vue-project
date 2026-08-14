<?php

namespace App\UseCases;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\UseCases\CreateCourseUseCaseInterface;
use App\Dtos\CourseDto;
use App\Models\Course;

class CreateCourseUseCase implements CreateCourseUseCaseInterface
{
    private CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function execute(CourseDto $dto): Course
    {
        return $this->courseRepository->createCourse($dto);
    }
}