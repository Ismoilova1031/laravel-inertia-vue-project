<?php

namespace App\Application\Student\GetStudents;

use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\StudentId;

final class GetStudentUseCase
{
    public function __construct(private readonly StudentQueryInterface $query) {}

    public function execute(int $id): StudentListItem
    {
        return $this->query->find($id)
            ?? throw StudentNotFoundException::withId(new StudentId($id));
    }
}