<?php

namespace App\Domain\Student;

use App\Domain\Student\VO\HashedPassword;

interface PasswordHasherInterface
{
    public function hash(string $plainPassword): HashedPassword;
}
