<?php

namespace App\Application\UseCase\Course;

use App\Domain\Course\CourseRepositoryInterface;
use Illuminate\Support\Collection;

final class GetCoursesUseCase
{
    public function __construct(private readonly CourseRepositoryInterface $courses) {}

    public function execute(): Collection
    {
        return $this->courses->all();
    }
}
