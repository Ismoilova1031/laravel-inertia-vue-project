<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\CourseId;
use App\Domain\CourseInterest\CourseInterest;
use App\Domain\CourseInterest\CourseInterestRepositoryInterface;
use App\Domain\CourseInterest\InterestType;
use App\Domain\CourseInterest\NewCourseInterest;
use App\Domain\Student\StudentId;
use App\Infrastructure\Eloquent\CourseInterestModel;
use RuntimeException;

final class EloquentCourseInterestRepository implements CourseInterestRepositoryInterface
{
    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?CourseInterest
    {
        $model = CourseInterestModel::query()
            ->where('course_id', $courseId->value())
            ->where('student_id', $studentId->value())
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function create(NewCourseInterest $new): CourseInterest
    {
        return $this->toEntity(CourseInterestModel::create([
            'course_id' => $new->courseId->value(),
            'student_id' => $new->studentId->value(),
            'is_liked' => $new->has(InterestType::LIKE),
            'is_planned' => $new->has(InterestType::PLANNED),
        ]));
    }

    public function save(CourseInterest $interest): void
    {
        $model = CourseInterestModel::find($interest->id())
            ?? throw new RuntimeException('CourseInterest topilmadi.');

        $model->update(
            [
                'course_id' => $interest->courseId()->value(),
                'student_id' => $interest->studentId()->value(),
                'is_liked' => $interest->has(InterestType::LIKE),
                'is_planned' => $interest->has(InterestType::PLANNED),
            ]
        );
    }

    public function delete(CourseInterest $interest): void
    {
        CourseInterestModel::whereKey($interest->id())->delete();
    }

    private function toEntity(CourseInterestModel $model): CourseInterest
    {
        return CourseInterest::reconstitute(
            (int) $model->id,
            new CourseId((int) $model->course_id),
            new StudentId((int) $model->student_id),
            (bool) $model->is_liked,
            (bool) $model->is_planned,
        );
    }
}
