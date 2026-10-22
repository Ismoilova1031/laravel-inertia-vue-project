<?php

namespace Tests\Feature\Application;

use App\Application\UseCase\Assessment\SaveTaskUseCase;
use App\Application\UseCase\Assessment\TaskData;
use App\Domain\Assessment\Exception\TaskLockedException;
use App\Domain\Assessment\TaskType;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\LessonModel;
use App\Infrastructure\Eloquent\StudentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class SaveTaskUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private function lessonId(): int
    {
        return LessonModel::factory()->create(['course_id' => CourseModel::factory()->create()->id])->id;
    }

    private function quiz(string $question = 'Savol'): TaskData
    {
        return new TaskData(TaskType::QUIZ->value, null, [[
            'question' => $question,
            'question_type' => 1,
            'points' => 5,
            'correct_answer' => null,
            'options' => [
                ['option' => 'A', 'is_correct' => true],
                ['option' => 'B', 'is_correct' => false],
            ],
        ]]);
    }

    private function save(int $lessonId, TaskData $data): void
    {
        $this->app->make(SaveTaskUseCase::class)->execute($lessonId, $data);
    }

    public function test_creates_quiz_with_questions_and_options(): void
    {
        $lessonId = $this->lessonId();

        $this->save($lessonId, $this->quiz());

        $this->assertDatabaseCount('tasks', 1);
        $this->assertDatabaseHas('questions', ['question' => 'Savol', 'points' => 5, 'sort_order' => 1]);
        $this->assertDatabaseCount('question_options', 2);
    }

    public function test_saving_again_replaces_questions_when_no_submissions(): void
    {
        $lessonId = $this->lessonId();
        $this->save($lessonId, $this->quiz('Eski'));

        $this->save($lessonId, $this->quiz('Yangi'));

        $this->assertDatabaseCount('tasks', 1);
        $this->assertDatabaseCount('questions', 1);
        $this->assertDatabaseHas('questions', ['question' => 'Yangi']);
    }

    public function test_changing_questions_is_rejected_when_submissions_exist(): void
    {
        $lessonId = $this->lessonId();
        $this->save($lessonId, $this->quiz('Eski'));
        $this->submit($lessonId);

        $this->expectException(TaskLockedException::class);

        $this->save($lessonId, $this->quiz('Yangi'));
    }

    private function submit(int $lessonId): void
    {
        $student = StudentModel::create([
            'name' => 'Ali', 'surname' => 'V', 'email' => 'a@example.com', 'username' => 'ali_v', 'password' => 'x',
        ]);

        DB::table('task_submissions')->insert([
            'task_id' => DB::table('tasks')->where('lesson_id', $lessonId)->value('id'),
            'student_id' => $student->id,
            'attempt' => 1,
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
