<?php

namespace App\Dtos;

use App\Models\Student;

class StudentResponseDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $surname,
        public string $email,
        public string $username,
    ) {}

    public static function fromModel(Student $student): self
    {
        return new self(
            id: $student->id,
            name: $student->name,
            surname: $student->surname,
            email: $student->email,
            username: $student->username,
        );
    }
}