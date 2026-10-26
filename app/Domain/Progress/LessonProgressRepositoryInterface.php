<?php

namespace App\Domain\Progress;

use App\Domain\Lesson\LessonId;
use App\Domain\Student\StudentId;

interface LessonProgressRepositoryInterface
{
    public function findByLessonAndStudent(LessonId $lessonId, StudentId $studentId): ?LessonProgress;

    /**
     * @param list<LessonId> $lessonIds
     * @return list<int> 
     */
    public function completedLessonIds(StudentId $studentId, array $lessonIds): array;

    public function create(NewLessonProgress $new): LessonProgress;
}