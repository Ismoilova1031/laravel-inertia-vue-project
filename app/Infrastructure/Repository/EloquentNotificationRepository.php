<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\VO\CourseId;
use App\Domain\Notification\NewNotification;
use App\Domain\Notification\Notification;
use App\Domain\Notification\VO\NotificationId;
use App\Domain\Notification\NotificationRepositoryInterface;
use App\Domain\Student\VO\StudentId;
use App\Infrastructure\Eloquent\NotificationModel;
use Illuminate\Support\Collection;

final class EloquentNotificationRepository implements NotificationRepositoryInterface
{
    public function create(NewNotification $new): void
    {
        NotificationModel::create([
            'student_id' => $new->studentId->value(),
            'course_id' => $new->courseId?->value(),
            'type' => $new->type,
            'title' => $new->title,
            'description' => $new->description,
        ]);
    }

    public function findByStudent(StudentId $studentId, int $limit = 50): Collection
    {
        return NotificationModel::where('student_id', $studentId->value())
            ->latest('id')->limit($limit)->get()
            ->map(fn(NotificationModel $model) => Notification::reconstitute(
                new NotificationId((int) $model->id),
                new StudentId((int) $model->student_id),
                $model->course_id !== null ? new CourseId((int) $model->course_id) : null,
                $model->type,
                $model->title,
                $model->description,
                $model->read_at,
                $model->created_at->toDateTimeImmutable(),
            ));
    }

    public function countUnread(StudentId $studentId): int
    {
        return NotificationModel::where('student_id', $studentId->value())->whereNull('read_at')->count();
    }

    public function markAsRead(StudentId $studentId, NotificationId $id): void
    {
        NotificationModel::where('student_id', $studentId->value())
            ->whereKey($id->value())->whereNull('read_at')->update(['read_at' => now()]);
    }

    public function markAllAsRead(StudentId $studentId): void
    {
        NotificationModel::where('student_id', $studentId->value())
            ->whereNull('read_at')->update(['read_at' => now()]);
    }
}
