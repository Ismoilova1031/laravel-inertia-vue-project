<?php

namespace App\Domain\Student;

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
