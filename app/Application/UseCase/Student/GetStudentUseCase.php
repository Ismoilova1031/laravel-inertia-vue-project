<?php

namespace App\Application\UseCase\Student;

use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;

final class GetStudentUseCase
{
    public function __construct(private readonly StudentRepositoryInterface $students) {}

    public function execute(int $id): Student
    {
        return $this->students->findById(new StudentId($id))
            ?? throw StudentNotFoundException::withId(new StudentId($id));
    }
}
