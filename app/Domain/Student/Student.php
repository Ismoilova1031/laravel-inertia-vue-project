<?php

namespace App\Domain\Student;

final class Student
{
    private function __construct(
        private ?StudentId $id,
        private PersonName $name,
        private EmailAddress $email,
        private Username $username,
        private HashedPassword $password,
    ){}

    public static function register(
        PersonName $name,
        EmailAddress $email,
        Username $username,
        HashedPassword $password,
    ): self {
        return new self(null, $name, $email, $username, $password);
    }
    
    public static function reconstitute(
        StudentId $id,
        PersonName $name,
        EmailAddress $email,
        Username $username,
        HashedPassword $password,
    ): self {
        return new self($id, $name, $email, $username, $password);
    }

    public function rename(PersonName $name): void
    {
        $this->name = $name;
    }

    public function changeEmail(EmailAddress $email): void
    {
        $this->email = $email;
    }

    public function changeUsername(Username $username): void
    {
        $this->username = $username;
    }

    public function changePassword(HashedPassword $password): void
    {
        $this->password = $password;
    }

    public function id(): ?StudentId { return $this->id; }
    public function name(): PersonName { return $this->name; }
    public function email(): EmailAddress { return $this->email; }
    public function username(): Username { return $this->username; }
    public function password(): HashedPassword { return $this->password; }
}