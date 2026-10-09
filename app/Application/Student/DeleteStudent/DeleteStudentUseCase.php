<?php

namespace App\Application\Student\DeleteStudent;

use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;

final class DeleteStudentUseCase
{
    public function __construct(Private readonly StudentRepositoryInterface $students) {}

    public function execute(int $id): void
    {
        $studentId = new StudentId($id);

        $this->students->findById($studentId)
            ?? throw StudentNotFoundException::withId($studentId);

        $this->students->delete($studentId);
    }

}