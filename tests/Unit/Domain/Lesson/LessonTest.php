<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Lesson;

use App\Domain\Course\VO\CourseId;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonContent;
use App\Domain\Lesson\VO\LessonDescription;
use App\Domain\Lesson\VO\LessonId;
use App\Domain\Lesson\VO\LessonTitle;
use App\Domain\Lesson\LessonType;
use App\Domain\Lesson\NewLesson;
use App\Domain\Lesson\VO\SortOrder;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class LessonTest extends TestCase
{
    private function lesson(LessonType $type, ?string $text = null, ?string $video = null): Lesson
    {
        return Lesson::reconstitute(
            new LessonId(1),
            new CourseId(1),
            new LessonTitle('Dars'),
            new LessonDescription('Tavsif'),
            LessonContent::of($type, $text, $video),
            new SortOrder(1),
        );
    }

    private function revise(Lesson $l, LessonType $type, ?string $text = null, ?string $video = null): void
    {
        $l->revise(new LessonTitle('Dars'), new LessonDescription('Tavsif'), $type, $text, $video);
    }

    public function test_text_content_keeps_text_and_drops_video(): void
    {
        $c = LessonContent::of(LessonType::TEXT, 'Matn', 'a.mp4');

        $this->assertSame('Matn', $c->text());
        $this->assertNull($c->videoPath());
    }

    public function test_video_content_keeps_video_and_drops_text(): void
    {
        $c = LessonContent::of(LessonType::VIDEO, 'Matn', 'a.mp4');

        $this->assertNull($c->text());
        $this->assertSame('a.mp4', $c->videoPath());
    }

    public function test_task_content_keeps_nothing(): void
    {
        $c = LessonContent::of(LessonType::TASK, 'Matn', 'a.mp4');

        $this->assertNull($c->text());
        $this->assertNull($c->videoPath());
    }

    public function test_new_lesson_carries_creation_data_without_id(): void
    {
        $new = NewLesson::create(
            new CourseId(1),
            new LessonTitle('Dars'),
            new LessonDescription('Tavsif'),
            LessonContent::of(LessonType::TEXT, 'Matn', null),
            new SortOrder(3),
        );

        $this->assertSame(3, $new->sortOrder->value());
        $this->assertSame('Matn', $new->content->text());
    }

    public function test_video_is_kept_when_no_new_video_given(): void
    {
        $l = $this->lesson(LessonType::VIDEO, null, 'a.mp4');

        $this->revise($l, LessonType::VIDEO);

        $this->assertSame('a.mp4', $l->videoPath());
    }

    public function test_new_video_replaces_old(): void
    {
        $l = $this->lesson(LessonType::VIDEO, null, 'a.mp4');

        $this->revise($l, LessonType::VIDEO, null, 'b.mp4');

        $this->assertSame('b.mp4', $l->videoPath());
    }

    public function test_changing_type_clears_previous_content(): void
    {
        $l = $this->lesson(LessonType::VIDEO, null, 'a.mp4');

        $this->revise($l, LessonType::TEXT, 'Matn');
        $this->assertNull($l->videoPath());

        $this->revise($l, LessonType::TASK, 'Matn');
        $this->assertNull($l->content());
        $this->assertNull($l->videoPath());
    }

    public function test_sort_order_must_be_positive(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new SortOrder(0);
    }
}
