<?php

namespace Tests\Feature\Application;

use App\Application\UseCase\Assessment\ReviewSubmissionUseCase;
use App\Application\UseCase\Assessment\SaveTaskUseCase;
use App\Application\UseCase\Assessment\SubmitTaskCommand;
use App\Application\UseCase\Assessment\SubmitTaskUseCase;
use App\Application\UseCase\Assessment\TaskData;
use App\Application\UseCase\Enrollment\EnrollStudentCommand;
use App\Application\UseCase\Enrollment\EnrollStudentUseCase;
use App\Application\UseCase\Progress\GetStudentCoursesUseCase;
use App\Application\UseCase\Progress\GetStudentLessonUseCase;
use App\Domain\Assessment\TaskType;
use App\Domain\Course\CourseStatus;
use App\Domain\Lesson\LessonType;
use App\Domain\Shared\UploadedFileData;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\LessonModel;
use App\Infrastructure\Eloquent\StudentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class TaskLessonProgressTest extends TestCase
{
    use RefreshDatabase;

    private int $courseId;
    private int $lessonId;
    private int $studentId;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $this->courseId = CourseModel::factory()->create(['status' => CourseStatus::PUBLISHED])->id;
        $this->lessonId = LessonModel::factory()->create([
            'course_id' => $this->courseId, 'lesson_type' => LessonType::TASK, 'sort_order' => 1,
        ])->id;
        $this->studentId = StudentModel::create([
            'name' => 'Ali', 'surname' => 'V', 'email' => 'a@example.com', 'username' => 'ali_v', 'password' => 'x',
        ])->id;

        $this->app->make(EnrollStudentUseCase::class)
            ->execute(new EnrollStudentCommand($this->courseId, $this->studentId));
    }

    private function saveTask(TaskData $data): int
    {
        $this->app->make(SaveTaskUseCase::class)->execute($this->lessonId, $data);

        return (int) DB::table('tasks')->where('lesson_id', $this->lessonId)->value('id');
    }

    private function submit(SubmitTaskCommand $command): int
    {
        return $this->app->make(SubmitTaskUseCase::class)->execute($command)->value();
    }

    private function percent(): int
    {
        return $this->app->make(GetStudentCoursesUseCase::class)->execute($this->studentId)[0]->progress->percent();
    }

    public function test_quiz_lesson_is_done_after_any_submission(): void
    {
        $taskId = $this->saveTask(new TaskData(TaskType::QUIZ->value, questions: [[
            'question' => 'S', 'question_type' => 1, 'points' => 5, 'correct_answer' => null,
            'options' => [['option' => 'A', 'is_correct' => true], ['option' => 'B', 'is_correct' => false]],
        ]]));
        $this->assertSame(0, $this->percent());

        $this->submit(new SubmitTaskCommand($taskId, $this->studentId, answers: []));

        $this->assertSame(100, $this->percent());
    }

    public function test_file_lesson_is_done_only_after_acceptance(): void
    {
        $taskId = $this->saveTask(new TaskData(TaskType::FILE->value, fileExtensions: ['pdf']));
        $tmp = tempnam(sys_get_temp_dir(), 'sub');
        file_put_contents($tmp, 'x');

        $id = $this->submit(new SubmitTaskCommand($taskId, $this->studentId, file: new UploadedFileData($tmp, 'pdf')));
        $this->assertSame(0, $this->percent());

        $this->app->make(ReviewSubmissionUseCase::class)->execute($id, true);
        $this->assertSame(100, $this->percent());
    }

    public function test_answers_are_revealed_only_after_last_attempt(): void
    {
        $taskId = $this->saveTask(new TaskData(TaskType::QUIZ->value, questions: [[
            'question' => 'S', 'question_type' => 1, 'points' => 5, 'correct_answer' => null,
            'options' => [['option' => 'A', 'is_correct' => true], ['option' => 'B', 'is_correct' => false]],
        ]]));
        $view = fn () => $this->app->make(GetStudentLessonUseCase::class)->execute($this->studentId, $this->lessonId)->task;

        $this->submit(new SubmitTaskCommand($taskId, $this->studentId, answers: []));
        $this->assertFalse($view()->revealAnswers);
        $this->assertSame(2, $view()->attemptsLeft);

        $this->submit(new SubmitTaskCommand($taskId, $this->studentId, answers: []));
        $this->submit(new SubmitTaskCommand($taskId, $this->studentId, answers: []));

        $this->assertTrue($view()->revealAnswers);
        $this->assertFalse($view()->canSubmit);
    }
}