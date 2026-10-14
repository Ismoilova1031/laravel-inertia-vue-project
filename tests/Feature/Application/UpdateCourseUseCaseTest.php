<?php

// tests/Feature/Application/UpdateCourseUseCaseTest.php
declare(strict_types=1);

namespace Tests\Feature\Application;

use App\Application\UseCase\Course\CreateCourseCommand;
use App\Application\UseCase\Course\CreateCourseUseCase;
use App\Application\UseCase\Course\UpdateCourseCommand;
use App\Application\UseCase\Course\UpdateCourseUseCase;
use App\Domain\Course\CourseStatus;
use App\Domain\Course\Exception\CannotPublishWithoutLessonsException;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UpdateCourseUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private function createCourse(): int
    {
        return $this->app->make(CreateCourseUseCase::class)
            ->execute(new CreateCourseCommand(title: 'PHP', description: 'Asoslar', category: 0))
            ->value();
    }

    private function update(int $id, int $status): void
    {
        $this->app->make(UpdateCourseUseCase::class)->execute(new UpdateCourseCommand(
            id: $id, title: 'PHP', description: 'Asoslar', category: 0, status: $status,
        ));
    }

    public function test_new_course_is_saved_as_draft(): void
    {
        $id = $this->createCourse();

        $this->assertDatabaseHas('courses', ['id' => $id, 'status' => CourseStatus::DRAFT->value]);
    }

    public function test_cannot_publish_course_without_lessons(): void
    {
        $id = $this->createCourse();

        $this->expectException(CannotPublishWithoutLessonsException::class);

        $this->update($id, CourseStatus::PUBLISHED->value);
    }

    public function test_publishes_course_with_a_lesson(): void
    {
        $id = $this->createCourse();
        Lesson::factory()->create(['course_id' => $id]);

        $this->update($id, CourseStatus::PUBLISHED->value);

        $this->assertDatabaseHas('courses', ['id' => $id, 'status' => CourseStatus::PUBLISHED->value]);
    }
}
