<?php

namespace App\Application\UseCase\Notification;

use App\Domain\Course\VO\CourseId;
use App\Domain\Notification\NewNotification;
use App\Domain\Notification\NotificationRepositoryInterface;
use App\Domain\Notification\NotificationType;
use App\Domain\Student\VO\StudentId;

final class Notifier
{
    public function __construct(private readonly NotificationRepositoryInterface $notifications) {}

    public function notify(StudentId $studentId, ?CourseId $courseId, NotificationType $type, string $title, ?string $description = null): void
    {
        $this->notifications->create(NewNotification::of($studentId, $courseId, $type, $title, $description));
    }
}