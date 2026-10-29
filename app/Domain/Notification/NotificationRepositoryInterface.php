<?php

namespace App\Domain\Notification;

use App\Domain\Student\VO\StudentId;
use App\Domain\Notification\VO\NotificationId;
use Illuminate\Support\Collection;

interface NotificationRepositoryInterface
{
    public function create(NewNotification $new): void;

    public function findByStudent(StudentId $studentId, int $limit = 50): Collection;
    
    public function countUnread(StudentId $studentId): int;

    public function markAsRead(StudentId $studentId, NotificationId $id): void;

    public function markAllAsRead(StudentId $studentId): void;
}