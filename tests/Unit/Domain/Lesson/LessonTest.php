<?php

namespace Tests\Unit\Domain\Lesson;

use App\Domain\Course\CourseId;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonDescription;
use App\Domain\Lesson\LessonTitle;
use App\Domain\Lesson\LessonType;
use App\Domain\Lesson\SortOrder;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class LessonTest extends TestCase
{
    private function lesson(LessonType $type, ?string $content = null, ?string $video = null): Lesson
    {
        return Lesson::create(
            new CourseId(1), new LessonTitle('Dars'), new LessonDescription('Tavsif'),
            $type, new SortOrder(1), $content, $video,
        );
    }

    private function revise(Lesson $l, LessonType $type, ?string $content = null, ?string $video = null): void
    {
        $l->revise(new LessonTitle('Dars'), new LessonDescription('Tavsif'), $type, $content, $video);
    }

    public function test_text_lesson_keeps_content_and_drops_video(): void
    {
        $l = $this->lesson(LessonType::TEXT, 'Matn', 'a.mp4');

        $this->assertSame('Matn', $l->content());
        $this->assertNull($l->videoPath());
    }

    public function test_video_lesson_drops_content(): void
    {
        $l = $this->lesson(LessonType::VIDEO, 'Matn', 'a.mp4');

        $this->assertNull($l->content());
        $this->assertSame('a.mp4', $l->videoPath());
    }

    public function test_video_is_kept_when_no_new_video_given(): void
    {
        $l = $this->lesson(LessonType::VIDEO, null, 'a.mp4');

        $this->revise($l, LessonType::VIDEO, null, null);

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