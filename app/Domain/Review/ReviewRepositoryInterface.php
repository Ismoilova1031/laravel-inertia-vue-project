<?php

namespace App\Domain\Review;

use App\Domain\Course\VO\CourseId;
use App\Domain\Student\VO\StudentId;

interface ReviewRepositoryInterface
{
    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?Review;

    public function create(NewReview $new): Review;

    public function save(Review $review): void;

    public function summary(CourseId $courseId): RatingSummary;
}