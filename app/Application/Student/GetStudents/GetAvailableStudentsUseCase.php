<?php

namespace App\Application\Student\GetStudents;

final class GetAvailableStudentsUseCase
{
    public function __construct(private readonly StudentQueryInterface $query){}

    /** @return list<StudentListItem> */
    public function execute(int $courseId): array
    {
        return $this->query->availableForCourse($courseId);
    }
}