<?php

namespace App\Domain\Student;

use App\Domain\Student\VO\EmailAddress;
use App\Domain\Student\VO\HashedPassword;
use App\Domain\Student\VO\PersonName;
use App\Domain\Student\VO\Username;

final class NewStudent
{
    private function __construct(
        public readonly PersonName $name,
        public readonly EmailAddress $email,
        public readonly Username $username,
        public readonly HashedPassword $password
    ) {}

    public static function register(
        PersonName $name,
        EmailAddress $email,
        Username $username,
        HashedPassword $password,
    ): self {
        return new self($name, $email, $username, $password);
    }
}
