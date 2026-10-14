<?php

namespace App\Infrastructure\Query;

use App\Application\UseCase\Student\StudentListItem;
use App\Application\UseCase\Student\StudentQueryInterface;
use App\Infrastructure\Eloquent\StudentModel;
use App\Domain\Enrollment\EnrollmentStatus;

final class EloquentStudentQuery implements StudentQueryInterface
{
    public function all(): array
    {
        return StudentModel::query()
            ->orderBy('surname')
            ->orderBy('name')
            ->get()
            ->map(fn (StudentModel $m) => new StudentListItem(
                (int) $m->id, $m->name, $m->surname, $m->email, $m->username,
            ))
            ->all();
    }

    public function find(int $id): ?StudentListItem
    {
        $model = StudentModel::query()->find($id, ['id', 'name', 'surname', 'email', 'username']);

        return $model ?
            new StudentListItem((int) $model->id, $model->name, $model->surname, $model->email, $model->username) :
            null;
    }

    public function availableForCourse(int $courseId): array
    {
        return StudentModel::query()
            ->whereNotIn('id', fn ($q) => $q
                ->select('student_id')
                ->from('course_student')
                ->where('course_id', $courseId))
            ->orderBy('surname')
            ->orderBy('name')
            ->get(['id', 'name', 'surname', 'email', 'username'])
            ->map(fn (StudentModel $m) => new StudentListItem(
                (int) $m->id, $m->name, $m->surname, $m->email, $m->username,
            ))
            ->all();
    }
}