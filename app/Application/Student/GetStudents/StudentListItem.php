<?php

namespace App\Application\Student\GetStudents;

final class StudentListItem
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $surname,
        public readonly string $email,
        public readonly string $username
    ){}
}