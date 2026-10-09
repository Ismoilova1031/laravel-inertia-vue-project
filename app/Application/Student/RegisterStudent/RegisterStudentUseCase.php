<?php

namespace App\Application\Student\RegisterStudent;

use App\Domain\Student\EmailAddress;
use App\Domain\Student\Exception\EmailAlreadyTakenException;
use App\Domain\Student\Exception\UsernameAlreadyTakenException;
use App\Domain\Student\PasswordHasherInterface;
use App\Domain\Student\PersonName;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use App\Domain\Student\Username;

final class RegisterStudentUseCase
{
    public function __construct(
        private readonly StudentRepositoryInterface $students,
        private readonly PasswordHasherInterface $hasher
    ){}

    public function execute(RegisterStudentCommand $command): StudentId
    {
        $email = new EmailAddress($command->email);
        $username = new Username($command->username);

        if($this->students->existsByEmail($email)){
            throw EmailAlreadyTakenException::withEmail($email);
        }

        if($this->students->existsByUsername($username)){
            throw UsernameAlreadyTakenException::withUsername($username);
        }

        $student = Student::register(
            new PersonName($command->name, $command->surname),
            $email,
            $username,
            $this->hasher->hash($command->password)
        );

        return $this->students->save($student)->id();
    }
}