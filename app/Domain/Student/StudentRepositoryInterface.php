<?php

namespace App\Domain\Student;

use App\Domain\Student\VO\EmailAddress;
use App\Domain\Student\VO\StudentId;
use App\Domain\Student\VO\Username;
use Illuminate\Support\Collection;

interface StudentRepositoryInterface
{
    public function findById(StudentId $id): ?Student;

    public function create(NewStudent $newStudent): Student;

    public function save(Student $student): void;

    public function delete(StudentId $id): void;

    public function existsByUsername(Username $username, ?StudentId $exceptId = null): bool;

    public function existsByEmail(EmailAddress $email, ?StudentId $exceptId = null): bool;

    /** @return Collection<int, Student> */
    public function all(): Collection;

    /** @param list<StudentId> $ids @return Collection<int, Student> */
    public function findByIds(array $ids): Collection;

    /** @param list<StudentId> $ids @return Collection<int, Student> */
    public function findExcludingIds(array $ids): Collection;
}
