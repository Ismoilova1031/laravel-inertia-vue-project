<?php

namespace App\Infrastructure\Repository;

use App\Domain\Student\EmailAddress;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\NewStudent;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\Student\Username;
use App\Infrastructure\Eloquent\StudentModel;
use App\Infrastructure\Mapper\StudentMapper;

final class EloquentStudentRepository implements StudentRepositoryInterface
{
    public function __construct(private readonly StudentMapper $mapper) {}

    public function findById(StudentId $id): ?Student
    {
        $model = StudentModel::find($id->value());

        return $model ? $this->mapper->toEntity($model) : null;
    }

    public function create(NewStudent $new): Student
    {
        return $this->mapper->toEntity(StudentModel::create($this->mapper->toNewAttributes($new)));
    }

    public function save(Student $student): void
    {
        $model = StudentModel::find($student->id()->value())
            ?? throw StudentNotFoundException::withId($student->id());

        $model->update($this->mapper->toAttributes($student));
    }

    public function delete(StudentId $id): void
    {
        StudentModel::whereKey($id->value())->delete();
    }

    public function existsByUsername(Username $username, ?StudentId $exceptId = null): bool
    {
        return StudentModel::where('username', $username->value())
            ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId->value()))
            ->exists();
    }

    public function existsByEmail(EmailAddress $email, ?StudentId $exceptId = null): bool
    {
        return StudentModel::where('email', $email->value())
            ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId->value()))
            ->exists();
    }
}
