<?php

// tests/Feature/Application/SubmitTaskUseCaseTest.php
declare(strict_types=1);

namespace Tests\Feature\Application;

use App\Application\UseCase\Assessment\GradeAnswerUseCase;
use App\Application\UseCase\Assessment\ReviewSubmissionUseCase;
use App\Application\UseCase\Assessment\SaveTaskUseCase;
use App\Application\UseCase\Assessment\SubmitTaskCommand;
use App\Application\UseCase\Assessment\SubmitTaskUseCase;
use App\Application\UseCase\Assessment\TaskData;
use App\Application\UseCase\Enrollment\DeactivateEnrollmentCommand;
use App\Application\UseCase\Enrollment\DeactivateEnrollmentUseCase;
use App\Application\UseCase\Enrollment\EnrollStudentCommand;
use App\Application\UseCase\Enrollment\EnrollStudentUseCase;
use App\Domain\Assessment\Exception\SubmissionRuleException;
use App\Domain\Assessment\TaskType;
use App\Domain\Course\CourseStatus;
use App\Domain\Enrollment\Exception\StudentNotActiveInCourseException;
use App\Domain\Shared\UploadedFileData;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\LessonModel;
use App\Infrastructure\Eloquent\StudentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class SubmitTaskUseCaseTest extends TestCase
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
        $this->lessonId = LessonModel::factory()->create(['course_id' => $this->courseId])->id;
        $this->studentId = StudentModel::create([
            'name' => 'Ali',
            'surname' => 'V',
            'email' => 'a@example.com',
            'username' => 'ali_v',
            'password' => 'x',
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

    private function choiceQuiz(): int
    {
        return $this->saveTask(new TaskData(TaskType::QUIZ->value, questions: [[
            'question' => 'Savol',
            'question_type' => 1,
            'points' => 5,
            'correct_answer' => null,
            'options' => [['option' => 'A', 'is_correct' => true], ['option' => 'B', 'is_correct' => false]],
        ]]));
    }

    private function fileTask(): int
    {
        return $this->saveTask(new TaskData(TaskType::FILE->value, fileExtensions: ['pdf']));
    }

    private function pdf(): UploadedFileData
    {
        $tmp = tempnam(sys_get_temp_dir(), 'sub');
        file_put_contents($tmp, 'data');

        return new UploadedFileData($tmp, 'pdf');
    }

    public function test_quiz_is_graded_automatically(): void
    {
        $taskId = $this->choiceQuiz();
        $questionId = (int) DB::table('questions')->value('id');
        $correct = (int) DB::table('question_options')->where('is_correct', true)->value('id');

        $id = $this->submit(new SubmitTaskCommand($taskId, $this->studentId, answers: [$questionId => ['options' => [$correct]]]));

        $this->assertDatabaseHas('task_submissions', ['id' => $id, 'status' => 1, 'score' => 5, 'attempt' => 1]);
        $this->assertDatabaseHas('student_answers', ['task_submission_id' => $id, 'score' => 5]);
    }

    public function test_fourth_quiz_attempt_is_rejected(): void
    {
        $taskId = $this->choiceQuiz();

        for ($i = 0; $i < 3; $i++) {
            $this->submit(new SubmitTaskCommand($taskId, $this->studentId, answers: []));
        }

        $this->expectException(SubmissionRuleException::class);

        $this->submit(new SubmitTaskCommand($taskId, $this->studentId, answers: []));
    }

    public function test_passive_student_cannot_submit(): void
    {
        $taskId = $this->choiceQuiz();
        $this->app->make(DeactivateEnrollmentUseCase::class)
            ->execute(new DeactivateEnrollmentCommand($this->courseId, $this->studentId));

        $this->expectException(StudentNotActiveInCourseException::class);

        $this->submit(new SubmitTaskCommand($taskId, $this->studentId, answers: []));
    }

    public function test_file_submission_flow(): void
    {
        $taskId = $this->fileTask();

        $first = $this->submit(new SubmitTaskCommand($taskId, $this->studentId, file: $this->pdf()));
        $this->assertDatabaseHas('task_submissions', ['id' => $first, 'status' => 0]);
        $this->assertTrue(Storage::disk('local')->exists(DB::table('task_submissions')->where('id', $first)->value('file_path')));

        try {
            $this->submit(new SubmitTaskCommand($taskId, $this->studentId, file: $this->pdf()));
            $this->fail('Pending paytida yangi topshirish rad etilishi kerak.');
        } catch (SubmissionRuleException) {
        }

        $this->app->make(ReviewSubmissionUseCase::class)->execute($first, false, 'Bo\'sh fayl');

        $second = $this->submit(new SubmitTaskCommand($taskId, $this->studentId, file: $this->pdf()));
        $this->assertDatabaseHas('task_submissions', ['id' => $second, 'attempt' => 2]);

        $this->app->make(ReviewSubmissionUseCase::class)->execute($second, true);
        $this->assertDatabaseHas('task_submissions', ['id' => $second, 'status' => 1]);
        $this->assertDatabaseHas('task_submissions', ['id' => $first, 'status' => 2]);
    }

    public function test_file_with_wrong_extension_is_rejected_and_nothing_is_stored(): void
    {
        $taskId = $this->fileTask();
        $tmp = tempnam(sys_get_temp_dir(), 'sub');

        try {
            $this->submit(new SubmitTaskCommand($taskId, $this->studentId, file: new UploadedFileData($tmp, 'exe')));
            $this->fail('Kengaytma rad etilishi kerak.');
        } catch (SubmissionRuleException) {
        }

        $this->assertDatabaseCount('task_submissions', 0);
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public function test_discussion_is_accepted_immediately_once(): void
    {
        $taskId = $this->saveTask(new TaskData(TaskType::DISCUSSION->value));

        $id = $this->submit(
            new SubmitTaskCommand($taskId, $this->studentId, text: 'Mening fikrim')
        );

        $this->assertDatabaseHas('task_submissions', [
            'id' => $id,
            'status' => 1,
        ]);

        $this->expectException(SubmissionRuleException::class);

        $this->submit(
            new SubmitTaskCommand($taskId, $this->studentId, text: 'Yana')
        );
    }

    public function test_teacher_grades_open_ended_answer(): void
    {
        $taskId = $this->saveTask(new TaskData(TaskType::QUIZ->value, questions: [[
            'question' => 'Tushuntiring',
            'question_type' => 4,
            'points' => 10,
            'correct_answer' => null,
            'options' => [],
        ]]));
        $questionId = (int) DB::table('questions')->value('id');

        $id = $this->submit(new SubmitTaskCommand($taskId, $this->studentId, answers: [$questionId => ['text' => 'Javob']]));
        $this->assertDatabaseHas('task_submissions', ['id' => $id, 'status' => 0, 'score' => null]);

        $this->app->make(GradeAnswerUseCase::class)->execute($id, $questionId, 8, 'Yaxshi');

        $this->assertDatabaseHas('task_submissions', ['id' => $id, 'status' => 1, 'score' => 8]);
        $this->assertDatabaseHas('student_answers', ['task_submission_id' => $id, 'score' => 8, 'teacher_comment' => 'Yaxshi']);
    }
}
