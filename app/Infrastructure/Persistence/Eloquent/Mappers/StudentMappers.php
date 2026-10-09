<?php

namespace App\Infrastructure\Persistence\Eloquent\Mappers;

use App\Domain\Student\EmailAddress;
use App\Domain\Student\HashedPassword;
use App\Domain\Student\PersonName;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\Username;
use App\Infrastructure\Persistence\Eloquent\Models\StudentModel;

final class StudentMappers
{
    public function toEntity(StudentModel $model): Student
    {
        return Student::reconstitute(
            new StudentId((int) $model->id),
            new PersonName($model->name, $model->surname),
            new EmailAddress($model->email),
            new Username($model->username),
            new HashedPassword($model->password),
        );
    }

    /** @return array<string, string> */
    public function toAttributes(Student $student): array
    {
        return [
            'name' => $student->name()->name(),
            'surname' => $student->name()->surname(),
            'email' => $student->email()->value(),
            'username' => $student->username()->value(),
            'password' => $student->password()->value(),
        ];
    }
}