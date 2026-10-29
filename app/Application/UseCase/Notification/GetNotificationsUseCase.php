<?php

namespace App\Application\UseCase\Notification;

use App\Domain\Notification\Notification;
use App\Domain\Notification\NotificationRepositoryInterface;
use App\Domain\Student\VO\StudentId;
use Illuminate\Support\Collection;

final class GetNotificationsUseCase
{
    public function __construct(private readonly NotificationRepositoryInterface $notifications) {}

    /** @return Collection<int, Notification> */
    public function execute(int $studentId): Collection
    {
        return $this->notifications->findByStudent(new StudentId($studentId));
    }
}