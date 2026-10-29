<?php

namespace App\Application\UseCase\Notification;

use App\Domain\Notification\VO\NotificationId;
use App\Domain\Notification\NotificationRepositoryInterface;
use App\Domain\Student\VO\StudentId;

final class MarkNotificationsReadUseCase
{
    public function __construct(private readonly NotificationRepositoryInterface $notifications) {}

    public function execute(int $studentId, ?int $notificationId = null): void
    {
        $student = new StudentId($studentId);

        $notificationId === null
            ? $this->notifications->markAllAsRead($student)
            : $this->notifications->markAsRead($student, new NotificationId($notificationId));
    }
}