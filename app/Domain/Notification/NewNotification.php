<?php

namespace App\Domain\Notification;

use App\Domain\Course\VO\CourseId;
use App\Domain\Student\VO\StudentId;

final class NewNotification
{
    private function __construct(
        public readonly StudentId $studentId,
        public readonly ?CourseId $courseId,
        public readonly NotificationType $type,
        public readonly string $title,
        public readonly ?string $description,
    ) {}

    public static function of(
        StudentId $studentId,
        ?CourseId $courseId,
        NotificationType $type,
        string $title,
        ?string $description = null,
    ): self {
        return new self($studentId, $courseId, $type, trim($title), $description);
    }
}