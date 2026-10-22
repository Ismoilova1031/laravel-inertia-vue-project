<?php

namespace App\Application\UseCase\Course;

use App\Domain\Course\Course;
use App\Domain\Lesson\Lesson;

final class CourseDetail
{
    /**
     * @param  list<Lesson>  $lessons
     * @param  list<CourseMember>  $members
     */
    public function __construct(
        public readonly Course $course,
        public readonly array $lessons,
        public readonly array $members,
    ) {}
}
