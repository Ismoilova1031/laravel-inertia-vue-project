<?php

namespace App\Infrastructure\Query;

use App\Application\UseCase\Course\CourseLessonCounterInterface;
use App\Domain\Course\CourseId;
use Illuminate\Support\Facades\DB;

final class EloquentCourseLessonCounter implements CourseLessonCounterInterface
{
    public function count(CourseId $courseId): int
    {
        return DB::table('lessons')
            ->where('course_id', $courseId->value())
            ->count();
    }
}
