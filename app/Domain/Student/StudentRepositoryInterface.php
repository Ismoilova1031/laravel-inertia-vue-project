<?php

namespace App\Domain\Student;

interface StudentRepositoryInterface
{
    public function findById(StudentId $id): ?Student;

    public function create(NewStudent $newStudent): Student;

    public function save(Student $student): void;

    public function delete(StudentId $id): void;

    public function existsByUsername(Username $username, ?StudentId $exceptId = null): bool;

    public function existsByEmail(EmailAddress $email, ?StudentId $exceptId = null): bool;
}
