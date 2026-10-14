<?php

namespace App\Application\UseCase\Student;

final class GetStudentsUseCase
{
    public function __construct(private readonly StudentQueryInterface $studentQuery) {}

    /** @return list<StudentListItem> */
    public function execute(): array
    {
        return $this->studentQuery->all();
    }
}
