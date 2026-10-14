<?php

namespace App\Application\UseCase\Course;

use App\Domain\Course\Course;
use App\Domain\Course\CourseCategory;
use App\Domain\Course\CourseDescription;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\CourseTitle;

final class CreateCourseUseCase
{
    public function __construct(private readonly CourseRepositoryInterface $course) {}

    public function execute(CreateCourseCommand $command): CourseId
    {
        $course = Course::register(
            new CourseTitle($command->title),
            new CourseDescription($command->description),
            CourseCategory::from($command->category)
        );

        return $this->course->save($course)->id();
    }
}
