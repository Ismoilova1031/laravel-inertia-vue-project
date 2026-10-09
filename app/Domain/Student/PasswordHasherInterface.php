<?php

namespace App\Domain\Student;

interface PasswordHasherInterface
{
    public function hash(string $plainPassword): HashedPassword;
}