<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Course\CourseId;
use App\Domain\CourseInterest\CourseInterest;
use App\Domain\CourseInterest\InterestType;
use App\Domain\Student\StudentId;
use App\Infrastructure\Eloquent\CourseInterestModel;

final class CourseInterestMapper
{
    public function toEntity(CourseInterestModel $model): CourseInterest
    {
        return CourseInterest::reconstitute(
            (int) $model->id,
            new CourseId((int) $model->course_id),
            new StudentId((int) $model->student_id),
            (bool) $model->is_liked,
            (bool) $model->is_planned,
        );
    }

    public function toAttributes(CourseInterest $courseInterest): array
    {
        return [
            'course_id' => $courseInterest->courseId()->value(),
            'student_id' => $courseInterest->studentId()->value(),
            'is_liked' => $courseInterest->has(InterestType::LIKE),
            'is_planned' => $courseInterest->has(InterestType::PLANNED),
        ];
    }
}