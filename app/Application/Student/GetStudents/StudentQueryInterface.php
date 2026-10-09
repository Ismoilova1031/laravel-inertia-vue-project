<?php

namespace App\Application\Student\GetStudents;

use App\Application\Student\GetStudents\StudentListItem;

interface StudentQueryInterface
{
    /**
     * @return list<StudentListItem>
     */
    public function all(): array;
}