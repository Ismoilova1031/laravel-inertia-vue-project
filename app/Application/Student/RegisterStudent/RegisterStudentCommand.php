<?php

namespace App\Application\Student\RegisterStudent;

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