<?php

namespace App\Application\UseCase\Course;

use App\Domain\Course\CourseId;

interface CourseLessonCounterInterface
{
    public function count(CourseId $courseId): int;
}
