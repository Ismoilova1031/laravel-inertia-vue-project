<?php

namespace App\Application\UseCase\Course;

use App\Domain\Course\CourseCategory;
use App\Domain\Course\CourseDescription;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\CourseStatus;
use App\Domain\Course\CourseTitle;
use App\Domain\Course\Exception\CourseNotFoundException;

final class UpdateCourseUseCase
{
    public function __construct(
        private readonly CourseRepositoryInterface $courses,
        private readonly CourseLessonCounterInterface $lessonCounter,
    ) {}

    public function execute(UpdateCourseCommand $command): void
    {
        $id = new CourseId($command->id);

        $course = $this->courses->findById($id)
            ?? throw CourseNotFoundException::withId($id);

        $course->changeDetails(
            new CourseTitle($command->title),
            new CourseDescription($command->description),
            CourseCategory::from($command->category),
        );

        $course->changeStatus(
            CourseStatus::from($command->status),
            $this->lessonCounter->count($id)
        );

        $this->courses->save($course);
    }
}
