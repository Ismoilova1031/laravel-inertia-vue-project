<?php

namespace App\Application\UseCase\Student;

final class GetAvailableStudentsUseCase
{
    public function __construct(private readonly StudentQueryInterface $query) {}

    /** @return list<StudentListItem> */
    public function execute(int $courseId): array
    {
        return $this->query->availableForCourse($courseId);
    }
}
