<?php

namespace App\Application\Student\UpdateStudent;

final class UpdateStudentCommand
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $surname,
        public readonly string $email,
        public readonly string $username,
        public readonly ?string $password = null,
    ){}
}