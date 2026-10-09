<?php

namespace App\Application\Student\GetStudents;

final class GetStudentsUseCase
{
    public function __construct(private readonly StudentQueryInterface $studentQuery){}

    /** @return list<StudentListItem> */
    public function execute(): array
    {
        return $this->studentQuery->all();
    }
}