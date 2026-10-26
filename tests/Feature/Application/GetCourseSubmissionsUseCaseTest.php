<?php

namespace Tests\Feature\Application;

use App\Application\UseCase\Assessment\GetCourseSubmissionsUseCase;
use App\Application\UseCase\Assessment\ReviewSubmissionUseCase;
use App\Application\UseCase\Assessment\SaveTaskUseCase;
use App\Application\UseCase\Assessment\SubmitTaskCommand;
use App\Application\UseCase\Assessment\SubmitTaskUseCase;
use App\Application\UseCase\Assessment\TaskData;
use App\Application\UseCase\Enrollment\EnrollStudentCommand;
use App\Application\UseCase\Enrollment\EnrollStudentUseCase;
use App\Domain\Assessment\TaskType;
use App\Domain\Course\CourseStatus;
use App\Domain\Lesson\LessonType;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\LessonModel;
use App\Infrastructure\Eloquent\StudentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class GetCourseSubmissionsUseCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_course_submissions_with_pending_first(): void
    {
        $courseId = CourseModel::factory()->create(['status' => CourseStatus::PUBLISHED])->id;
        $lessonId = LessonModel::factory()->create(['course_id' => $courseId, 'lesson_type' => LessonType::TASK])->id;
        $studentId = StudentModel::create([
            'name' => 'Ali', 'surname' => 'V', 'email' => 'a@example.com', 'username' => 'ali_v', 'password' => 'x',
        ])->id;
        $this->app->make(EnrollStudentUseCase::class)->execute(new EnrollStudentCommand($courseId, $studentId));

        $this->app->make(SaveTaskUseCase::class)->execute($lessonId, new TaskData(TaskType::DISCUSSION->value));
        $taskId = (int) DB::table('tasks')->value('id');

        $first = $this->app->make(SubmitTaskUseCase::class)
            ->execute(new SubmitTaskCommand($taskId, $studentId, text: 'Fikr'))->value();

        $rows = $this->app->make(GetCourseSubmissionsUseCase::class)->execute($courseId);

        $this->assertCount(1, $rows);
        $this->assertSame($first, $rows[0]->submission->id()->value());
        $this->assertSame('Ali V', $rows[0]->student->name()->fullName());
    }
}