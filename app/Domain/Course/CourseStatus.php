<?php

namespace App\Domain\Course;

enum CourseStatus: int
{
    case DRAFT = 0;
    case PUBLISHED = 1;
    case ARCHIVED = 2;

    public function canTransitionTo(self $target): bool
    {
        return match ($this) {
            self::DRAFT => in_array($target, [self::PUBLISHED, self::ARCHIVED], true),
            self::PUBLISHED => $target === self::ARCHIVED,
            self::ARCHIVED => false,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::ARCHIVED => 'Archived',
        };
    }

    public static function labels(): array
    {
        return collect(self::cases())->map(fn ($case) => ['label' => $case->label(), 'value' => $case->value])->toArray();
    }
}
