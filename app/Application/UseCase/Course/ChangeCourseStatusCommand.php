<?php

namespace App\Application\UseCase\Course;

final class ChangeCourseStatusCommand
{
    public function __construct(
        public readonly int $id,
        public readonly int $status,
    ) {}
}
