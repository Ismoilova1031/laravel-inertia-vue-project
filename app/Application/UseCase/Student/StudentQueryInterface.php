<?php

namespace App\Application\UseCase\Student;

interface StudentQueryInterface
{
    /**
     * @return list<StudentListItem>
     */
    public function all(): array;

    public function find(int $id): ?StudentListItem;

    /** @return list<StudentListItem> */
    public function availableForCourse(int $courseId): array;
}
