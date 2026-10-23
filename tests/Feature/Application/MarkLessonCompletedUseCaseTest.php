<?php

namespace Tests\Feature\Application;

use App\Application\UseCase\Enrollment\DeactivateEnrollmentCommand;
use App\Application\UseCase\Enrollment\DeactivateEnrollmentUseCase;
use App\Application\UseCase\Enrollment\EnrollStudentCommand;
use App\Application\UseCase\Enrollment\EnrollStudentUseCase;
use App\Application\UseCase\Progress\GetStudentCoursesUseCase;
use App\Application\UseCase\Progress\MarkLessonCompletedUseCase;
use App\Domain\Course\CourseStatus;
use App\Domain\Enrollment\Exception\StudentNotActiveInCourseException;
use App\Domain\Lesson\LessonType;
use App\Domain\Progress\Exception\LessonNotCompletableManuallyException;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\LessonModel;
use App\Infrastructure\Eloquent\StudentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class MarkLessonCompletedUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private int $courseId;
    private int $studentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->courseId = CourseModel::factory()->create(['status' => CourseStatus::PUBLISHED])->id;
        $this->studentId = StudentModel::create([
            'name' => 'Ali', 'surname' => 'V', 'email' => 'a@example.com', 'username' => 'ali_v', 'password' => 'x',
        ])->id;

        $this->app->make(EnrollStudentUseCase::class)
            ->execute(new EnrollStudentCommand($this->courseId, $this->studentId));
    }

    private function lesson(int $sort, LessonType $type = LessonType::TEXT): int
    {
        return LessonModel::factory()->create([
            'course_id' => $this->courseId, 'lesson_type' => $type, 'sort_order' => $sort,
        ])->id;
    }

    private function mark(int $lessonId): void
    {
        $this->app->make(MarkLessonCompletedUseCase::class)->execute($lessonId, $this->studentId);
    }

    public function test_marks_lesson_once_and_is_idempotent(): void
    {
        $lesson = $this->lesson(1);

        $this->mark($lesson);
        $this->mark($lesson);

        $this->assertDatabaseCount('lesson_progress', 1);
    }

    public function test_task_lesson_cannot_be_marked(): void
    {
        $lesson = $this->lesson(1, LessonType::TASK);

        $this->expectException(LessonNotCompletableManuallyException::class);

        $this->mark($lesson);
    }

    public function test_passive_student_cannot_mark(): void
    {
        $lesson = $this->lesson(1);
        $this->app->make(DeactivateEnrollmentUseCase::class)
            ->execute(new DeactivateEnrollmentCommand($this->courseId, $this->studentId));

        $this->expectException(StudentNotActiveInCourseException::class);

        $this->mark($lesson);
    }

    public function test_student_courses_report_progress(): void
    {
        $first = $this->lesson(1);
        $this->lesson(2);
        $this->mark($first);

        $items = $this->app->make(GetStudentCoursesUseCase::class)->execute($this->studentId);

        $this->assertCount(1, $items);
        $this->assertSame(50, $items[0]->progress->percent());
    }
}