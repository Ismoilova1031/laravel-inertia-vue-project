<?php

namespace App\Application\UseCase\Student;

final class RegisterStudentCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $surname,
        public readonly string $email,
        public readonly string $username,
        public readonly string $password
    ){}
}