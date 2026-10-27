<?php

namespace App\Application\UseCase\Student;

use App\Domain\Student\VO\EmailAddress;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\PasswordHasherInterface;
use App\Domain\Student\VO\PersonName;
use App\Domain\Student\VO\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\Student\VO\Username;

final class UpdateStudentUseCase
{
    public function __construct(
        private readonly StudentRepositoryInterface $students,
        private readonly PasswordHasherInterface $hasher
    ) {}

    public function execute(UpdateStudentCommand $command): void
    {
        $id = new StudentId($command->id);

        $student = $this->students->findById($id)
            ?? throw StudentNotFoundException::withId($id);

        $email = new EmailAddress($command->email);
        $username = new Username($command->username);

        if ($this->students->existsByEmail($email, $id)) {
            throw new \DomainException("Email {$email->value()} is already in use.");
        }

        if ($this->students->existsByUsername($username, $id)) {
            throw new \DomainException("Username {$username->value()} is already in use.");
        }

        $student->rename(new PersonName($command->name, $command->surname));
        $student->changeEmail($email);
        $student->changeUsername($username);

        if ($command->password !== null && $command->password !== '') {
            $student->changePassword($this->hasher->hash($command->password));
        }

        $this->students->save($student);
    }
}
