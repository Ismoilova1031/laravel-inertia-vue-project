<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Student\EmailAddress;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\Student\Username;
use App\Infrastructure\Persistence\Eloquent\Mappers\StudentMappers;
use App\Infrastructure\Persistence\Eloquent\Models\StudentModel;

final class EloquentStudentRepository implements StudentRepositoryInterface
{
    public function __construct(private readonly StudentMappers $mapper) {}

    public function findById(StudentId $id): ?Student
    {
        $model = StudentModel::find($id->value());

        return $model ? $this->mapper->toEntity($model) : null;
    }

    public function save(Student $student): Student
    {
        $attributes = $this->mapper->toAttributes($student);

        if($student->id() === null){
            $model = StudentModel::create($attributes);
        }else{
            $model = StudentModel::find($student->id()->value()) ?? throw StudentNotFoundException::withId($student->id());

            $model->update($attributes);
        }

        return $this->mapper->toEntity($model);
    }

    public function delete(StudentId $id): void
    {
        StudentModel::whereKey($id->value())->delete();
    }

    public function existsByUsername(Username $username, StudentId $exceptId = null): bool
    {
        return StudentModel::where('username', $username->value())
            ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId->value()))
            ->exists();
    }

    public function existsByEmail(EmailAddress $email, StudentId $exceptId = null): bool
    {
        return StudentModel::where('email', $email->value())
            ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId->value()))
            ->exists();
    }
}