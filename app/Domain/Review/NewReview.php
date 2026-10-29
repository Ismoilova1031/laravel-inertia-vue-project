<?php

namespace App\Domain\Review;

use App\Domain\Course\VO\CourseId;
use App\Domain\Student\VO\StudentId;

final class NewReview
{
    private function __construct(
        public readonly CourseId $courseId,
        public readonly StudentId $studentId,
        public readonly Rating $rating,
        public readonly ReviewComment $comment,
    ) {}

    public static function write(
        CourseId $courseId,
        StudentId $studentId,
        Rating $rating,
        ReviewComment $comment,
    ): self {
        return new self($courseId, $studentId, $rating, $comment);
    }
}