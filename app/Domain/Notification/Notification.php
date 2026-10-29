<?php

namespace App\Domain\Notification;

use App\Domain\Course\VO\CourseId;
use App\Domain\Student\VO\StudentId;
use App\Domain\Notification\VO\NotificationId;
use DateTimeImmutable;

final class Notification
{
    private function __construct(
        private readonly NotificationId $id,
        private readonly StudentId $studentId,
        private readonly ?CourseId $courseId,
        private readonly NotificationType $type,
        private readonly string $title,
        private readonly ?string $description,
        private readonly ?DateTimeImmutable $readAt,
        private readonly DateTimeImmutable $createdAt,
    ){}

    public static function reconstitute(
        NotificationId $id, StudentId $studentId, ?CourseId $courseId, NotificationType $type, 
        string $title, ?string $description, ?DateTimeImmutable $readAt, DateTimeImmutable $createdAt
    ): self {
        return new self($id, $studentId, $courseId, $type, $title, $description, $readAt, $createdAt);
    }

    public function isRead(): bool { return $this->readAt !== null; }

    public function id(): NotificationId { return $this->id; }
    public function studentId(): StudentId { return $this->studentId; }
    public function courseId(): ?CourseId { return $this->courseId; }
    public function type(): NotificationType { return $this->type; }
    public function title(): string { return $this->title; }
    public function description(): ?string { return $this->description; }
    public function createdAt(): DateTimeImmutable { return $this->createdAt; }

}