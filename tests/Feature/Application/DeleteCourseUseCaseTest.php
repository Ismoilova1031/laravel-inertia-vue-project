<?php

declare(strict_types=1);

namespace Tests\Feature\Application;

use App\Application\UseCase\Course\CreateCourseCommand;
use App\Application\UseCase\Course\CreateCourseUseCase;
use App\Application\UseCase\Course\DeleteCourseUseCase;
use App\Domain\Course\CourseStatus;
use App\Domain\Course\Exception\CannotDeleteNonDraftCourseException;
use App\Infrastructure\Eloquent\CourseModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DeleteCourseUseCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_deletes_draft_course(): void
    {
        $id = $this->app->make(CreateCourseUseCase::class)
            ->execute(new CreateCourseCommand(title: 'PHP', description: 'Asoslar', category: 0))
            ->value();

        $this->app->make(DeleteCourseUseCase::class)->execute($id);

        $this->assertDatabaseMissing('courses', ['id' => $id]);
    }

    public function test_does_not_delete_published_course(): void
    {
        $course = CourseModel::factory()->create(['status' => CourseStatus::PUBLISHED]);

        try {
            $this->app->make(DeleteCourseUseCase::class)->execute($course->id);
            $this->fail('CannotDeleteNonDraftCourseException kutilgan edi.');
        } catch (CannotDeleteNonDraftCourseException) {
        }

        $this->assertDatabaseHas('courses', ['id' => $course->id]);
    }
}