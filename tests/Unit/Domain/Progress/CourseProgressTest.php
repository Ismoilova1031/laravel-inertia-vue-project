<?php

namespace Tests\Unit\Domain\Progress;

use App\Domain\Course\CourseId;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonContent;
use App\Domain\Lesson\LessonDescription;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonTitle;
use App\Domain\Lesson\LessonType;
use App\Domain\Lesson\SortOrder;
use App\Domain\Progress\CourseProgress;
use App\Domain\Progress\Exception\LessonNotCompletableManuallyException;
use App\Domain\Progress\NewLessonProgress;
use App\Domain\Student\StudentId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CourseProgressTest extends TestCase
{
    private function lesson(int $id, LessonType $type = LessonType::TEXT): Lesson
    {
        return Lesson::reconstitute(
            new LessonId($id), new CourseId(1), new LessonTitle("D$id"), new LessonDescription('T'),
            LessonContent::of($type, null, null), new SortOrder($id),
        );
    }

    public function test_percent_is_floored_and_next_is_first_incomplete(): void
    {
        $p = CourseProgress::of([$this->lesson(1), $this->lesson(2), $this->lesson(3)], [1]);

        $this->assertSame(33, $p->percent());
        $this->assertSame(2, $p->next()->id()->value());
        $this->assertFalse($p->isComplete());
    }

    public function test_complete_when_all_done(): void
    {
        $p = CourseProgress::of([$this->lesson(1), $this->lesson(2)], [2, 1]);

        $this->assertSame(100, $p->percent());
        $this->assertTrue($p->isComplete());
        $this->assertNull($p->next());
    }

    public function test_course_without_lessons_is_not_complete(): void
    {
        $p = CourseProgress::of([], []);

        $this->assertSame(0, $p->percent());
        $this->assertFalse($p->isComplete());
    }

    public function test_task_lesson_cannot_be_completed_manually(): void
    {
        $this->expectException(LessonNotCompletableManuallyException::class);

        NewLessonProgress::complete($this->lesson(1, LessonType::TASK), new StudentId(1), new DateTimeImmutable());
    }
}