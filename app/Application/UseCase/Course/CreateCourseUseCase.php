<?php

namespace App\Application\UseCase\Course;

use App\Domain\Course\CourseCategory;
use App\Domain\Course\VO\CourseDescription;
use App\Domain\Course\VO\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\VO\CourseTitle;
use App\Domain\Course\NewCourse;

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
