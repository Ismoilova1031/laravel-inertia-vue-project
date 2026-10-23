<?php

namespace App\Domain\Progress;

use App\Domain\Lesson\Lesson;

final class CourseProgress
{
    private function __construct(
        public readonly int $total,
        public readonly int $completed,
        private readonly ?Lesson $next
    ) {}

    public static function of(array $lessons, array $completedLessonIds): self
    {
        $done = array_flip($completedLessonIds);
        $completed = 0;
        $next = null;
        
        foreach($lessons as $lesson){
            if(isset($done[$lesson->id()->value()])){
                $completed++;
            } elseif($next === null) {
                $next = $lesson;
            }
        }

        return new self(count($lessons), $completed, $next);
    }

    public function percent(): int
    {
        return $this->total === 0 ? 0 : intdiv($this->completed * 100, $this->total);
    }

    public function isComplete(): bool
    {
        return $this->total > 0 && $this->completed === $this->total;
    }

    public function next(): ?Lesson { return $this->next; }
    
}