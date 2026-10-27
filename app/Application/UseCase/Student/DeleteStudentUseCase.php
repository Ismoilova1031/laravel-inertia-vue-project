<?php

namespace App\Application\UseCase\Student;

use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\VO\StudentId;
use App\Domain\Student\StudentRepositoryInterface;

final class DeleteStudentUseCase
{
    public function __construct(private readonly StudentRepositoryInterface $students) {}

    public function execute(int $id): void
    {
        $studentId = new StudentId($id);

        $this->students->findById($studentId)
            ?? throw StudentNotFoundException::withId($studentId);

        $this->students->delete($studentId);
    }
}
