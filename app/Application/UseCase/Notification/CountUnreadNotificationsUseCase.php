<?php

namespace App\Application\UseCase\Notification;

use App\Domain\Notification\NotificationRepositoryInterface;
use App\Domain\Student\VO\StudentId;

final class CountUnreadNotificationsUseCase
{
    public function __construct(private readonly NotificationRepositoryInterface $notifications) {}

    public function execute(int $studentId): int
    {
        return $this->notifications->countUnread(new StudentId($studentId));
    }
}