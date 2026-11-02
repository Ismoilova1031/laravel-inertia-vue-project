<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\VO\CourseId;
use App\Domain\Review\NewReview;
use App\Domain\Review\VO\Rating;
use App\Domain\Review\RatingSummary;
use App\Domain\Review\Review;
use App\Domain\Review\VO\ReviewComment;
use App\Domain\Review\VO\ReviewId;
use App\Domain\Review\ReviewRepositoryInterface;
use App\Domain\Student\VO\StudentId;
use App\Infrastructure\Eloquent\CourseReviewModel;
use RuntimeException;

final class EloquentReviewRepository implements ReviewRepositoryInterface
{
    public function findByCourseAndStudent(Courseid $courseId, StudentId $studentId): ?Review
    {
        $model = CourseReviewModel::where('course_id', $courseId->value())
            ->where('student_id', $studentId->value())->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function create(NewReview $new): Review
    {
        return $this->toEntity(CourseReviewModel::create([
            'course_id' => $new->courseId->value(),
            'student_id' => $new->studentId->value(),
            'rating' => $new->rating->value(),
            'comment' => $new->comment->value()
        ]));
    }

    public function save(Review $review): void
    {
        $model = CourseReviewModel::find($review->id()->value())
            ?? throw new RuntimeException('Review topilmadi');

        $model->update(['rating' => $review->rating()->value(), 'comment' => $review->comment()->value()]);
    }

    public function summary(CourseId $courseId): RatingSummary
    {
        $row = CourseReviewModel::where('course_id', $courseId->value())
            ->selectRaw('COUNT(*) as c, AVG(rating) as a')->first();

        return new RatingSummary(round((float) ($row->a ?? 0), 1), (int) ($row->c ?? 0));
    }

    public function summaries(array $courseIds): array
    {
        if ($courseIds === []) {
            return [];
        }

        return CourseReviewModel::whereIn('course_id', array_map(fn(CourseId $id) => $id->value(), $courseIds))
            ->groupBy('course_id')
            ->selectRaw('course_id, COUNT(*) as c, AVG(rating) as a')
            ->get()
            ->mapWithKeys(fn($r) => [
                (int) $r->course_id => new RatingSummary(round((float) $r->a, 1), (int) $r->c),
            ])
            ->all();
    }

    private function toEntity(CourseReviewModel $model): Review
    {
        return Review::reconstitute(
            new ReviewId((int) $model->id),
            new CourseId((int) $model->course_id),
            new StudentId((int) $model->student_id),
            new Rating((int) $model->rating),
            new ReviewComment($model->comment),
        );
    }
}
