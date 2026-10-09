<?php

namespace App\Infrastructure\Persistence\Eloquent\Queries;

use App\Application\Student\GetStudents\StudentListItem;
use App\Application\Student\GetStudents\StudentQueryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\StudentModel;

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
}