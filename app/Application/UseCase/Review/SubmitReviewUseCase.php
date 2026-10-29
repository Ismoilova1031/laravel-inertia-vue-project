<?php

namespace App\Application\UseCase\Review;

use App\Application\UseCase\Progress\CourseCompletion;
use App\Domain\Course\VO\CourseId;
use App\Domain\Review\Exception\ReviewNotAllowedException;
use App\Domain\Review\NewReview;
use App\Domain\Review\Rating;
use App\Domain\Review\ReviewComment;
use App\Domain\Review\ReviewRepositoryInterface;
use App\Domain\Student\VO\StudentId;

final class SubmitReviewUseCase
{
    public function __construct(
        private readonly ReviewRepositoryInterface $reviews,
        private readonly CourseCompletion $completion,
    ) {}

    public function execute(int $studentId, int $courseId, int $rating, ?string $comment): void
    {
        $studentId = new StudentId($studentId);
        $courseId = new CourseId($courseId);

        if (!$this->completion->progress($studentId, $courseId)->isComplete()) {
            throw ReviewNotAllowedException::create();
        }

        $rating = new Rating($rating);
        $comment = new ReviewComment($comment);

        $review = $this->reviews->findByCourseAndStudent($courseId, $studentId);

        if ($review === null) {
            $this->reviews->create(NewReview::write($courseId, $studentId, $rating, $comment));

            return;
        }

        $review->revise($rating, $comment);
        $this->reviews->save($review);
    }
}