<?php

namespace App\Application\UseCase\Course;

use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\Exception\CourseNotFoundException;

final class DeleteCourseUseCase
{
    public function __construct(private readonly CourseRepositoryInterface $courses) {}

    public function execute(int $id): void
    {
        $courseId = new CourseId($id);

        $this->courses->findById($courseId)
            ?? throw CourseNotFoundException::withId($courseId);

        $this->courses->delete($courseId);
    }
}
