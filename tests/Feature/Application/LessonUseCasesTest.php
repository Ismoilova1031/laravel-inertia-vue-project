<?php

// tests/Feature/Application/LessonUseCasesTest.php
declare(strict_types=1);

namespace Tests\Feature\Application;

use App\Domain\Shared\UploadedFileData;
use App\Application\UseCase\Lesson\CreateLessonCommand;
use App\Application\UseCase\Lesson\CreateLessonUseCase;
use App\Application\UseCase\Lesson\DeleteLessonUseCase;
use App\Application\UseCase\Lesson\ReorderLessonsCommand;
use App\Application\UseCase\Lesson\ReorderLessonsUseCase;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\Exception\LessonTaskRequiredException;
use App\Domain\Lesson\LessonType;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\LessonModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class LessonUseCasesTest extends TestCase
{
    use RefreshDatabase;

    private function create(int $courseId, LessonType $type, int $sort = 1, ?UploadedFileData $video = null): int
    {
        return (int) $this->app->make(CreateLessonUseCase::class)->execute(new CreateLessonCommand(
            courseId: $courseId,
            title: 'Dars',
            description: 'Tavsif',
            type: $type->value,
            content: $type === LessonType::TEXT ? 'Matn' : null,
            video: $video,
        ))->value();
    }

    public function test_creates_text_lesson(): void
    {
        $course = CourseModel::factory()->create();

        $id = $this->create($course->id, LessonType::TEXT);

        $this->assertDatabaseHas('lessons', ['id' => $id, 'lesson_content' => 'Matn', 'video_url' => null]);
    }

    public function test_stores_video_file_for_video_lesson(): void
    {
        Storage::fake('public');
        $course = CourseModel::factory()->create();
        $tmp = tempnam(sys_get_temp_dir(), 'vid');
        file_put_contents($tmp, 'video-bytes');

        $id = $this->create($course->id, LessonType::VIDEO, 1, new UploadedFileData($tmp, 'mp4'));

        $path = LessonModel::find($id)->video_url;
        $this->assertNotNull($path);
        $this->assertTrue(Storage::disk('public')->exists($path));
    }

    public function test_task_lesson_requires_task_data(): void
    {
        $course = CourseModel::factory()->create();

        $this->expectException(LessonTaskRequiredException::class);

        $this->create($course->id, LessonType::TASK);
    }

    public function test_delete_resequences_remaining_lessons(): void
    {
        $course = CourseModel::factory()->create();
        $first = $this->create($course->id, LessonType::TEXT, 1);
        $second = $this->create($course->id, LessonType::TEXT, 2);
        $third = $this->create($course->id, LessonType::TEXT, 3);

        $this->app->make(DeleteLessonUseCase::class)->execute($second);

        $this->assertDatabaseHas('lessons', ['id' => $first, 'sort_order' => 1]);
        $this->assertDatabaseHas('lessons', ['id' => $third, 'sort_order' => 2]);
        $this->assertDatabaseMissing('lessons', ['id' => $second]);
    }

    public function test_reorder_updates_positions(): void
    {
        $course = CourseModel::factory()->create();
        $a = $this->create($course->id, LessonType::TEXT, 1);
        $b = $this->create($course->id, LessonType::TEXT, 2);

        $this->app->make(ReorderLessonsUseCase::class)
            ->execute(new ReorderLessonsCommand($course->id, [$a => 2, $b => 1]));

        $this->assertDatabaseHas('lessons', ['id' => $a, 'sort_order' => 2]);
        $this->assertDatabaseHas('lessons', ['id' => $b, 'sort_order' => 1]);
    }

    public function test_reorder_rejects_lesson_of_another_course(): void
    {
        $course = CourseModel::factory()->create();
        $other = CourseModel::factory()->create();
        $foreign = $this->create($other->id, LessonType::TEXT, 1);

        $this->expectException(LessonNotFoundException::class);

        $this->app->make(ReorderLessonsUseCase::class)
            ->execute(new ReorderLessonsCommand($course->id, [$foreign => 1]));
    }
}
