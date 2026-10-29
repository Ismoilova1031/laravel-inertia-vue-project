<?php

namespace App\Domain\Review;

use App\Domain\Course\VO\CourseId;
use App\Domain\Student\VO\StudentId;
use App\Domain\Review\VO\ReviewId;

final class Review
{
    private function __construct(
        private readonly ReviewId $id,
        private readonly CourseId $courseId,
        private readonly StudentId $studentId,
        private Rating $rating,
        private ReviewComment $comment,
    ){}

    public static function reconstitute(
        ReviewId $id,
        CourseId $courseId,
        StudentId $studentId,
        Rating $rating,
        ReviewComment $comment,
    ): self {
        return new self($id, $courseId, $studentId, $rating, $comment);
    }

    public function revise(Rating $rating, ReviewComment $comment): void
    {
        $this->rating = $rating;
        $this->comment = $comment;
    }

    public function id(): ReviewId { return $this->id; }
    public function courseId(): CourseId { return $this->courseId; }
    public function studentId(): StudentId { return $this->studentId; }
    public function rating(): Rating { return $this->rating; }
    public function comment(): ReviewComment { return $this->comment; }
}