<?php

namespace App\Application\UseCase\Course;

use App\Domain\Course\NewCourse;
use App\Domain\Course\CourseCategory;
use App\Domain\Course\CourseDescription;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\CourseTitle;

final class CreateCourseUseCase
{
    public function __construct(private readonly CourseRepositoryInterface $courses) {}

    public function execute(CreateCourseCommand $command): CourseId
    {
        $new = NewCourse::draft(
            new CourseTitle($command->title),
            new CourseDescription($command->description),
            CourseCategory::from($command->category),
        );

        return $this->courses->create($new)->id();
    }
}
