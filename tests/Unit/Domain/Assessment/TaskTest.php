<?php

namespace Tests\Unit\Domain\Assessment;

use App\Domain\Assessment\AllowedFileExtensions;
use App\Domain\Assessment\Exception\InvalidQuestionException;
use App\Domain\Assessment\Exception\InvalidTaskException;
use App\Domain\Assessment\Exception\TaskLockedException;
use App\Domain\Assessment\FileExtensions;
use App\Domain\Assessment\Points;
use App\Domain\Assessment\Question;
use App\Domain\Assessment\QuestionOption;
use App\Domain\Assessment\QuestionType;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskType;
use App\Domain\Lesson\LessonId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class TaskTest extends TestCase
{
    private function single(string $text = 'Savol'): Question
    {
        return Question::create($text, QuestionType::SINGLE_CHOICE, new Points(5), null, [
            new QuestionOption('A', true),
            new QuestionOption('B', false),
        ]);
    }

    private function quiz(array $questions): Task
    {
        return Task::create(new LessonId(1), TaskType::QUIZ, null, $questions);
    }

    public function test_quiz_requires_a_question(): void
    {
        $this->expectException(InvalidTaskException::class);

        $this->quiz([]);
    }

    public function test_file_task_requires_extensions(): void
    {
        $this->expectException(InvalidTaskException::class);

        Task::create(new LessonId(1), TaskType::FILE, null, []);
    }

    public function test_single_choice_needs_exactly_one_correct_option(): void
    {
        $this->expectException(InvalidQuestionException::class);

        Question::create('S', QuestionType::SINGLE_CHOICE, new Points(1), null, [
            new QuestionOption('A', true),
            new QuestionOption('B', true),
        ]);
    }

    public function test_short_answer_requires_correct_answer(): void
    {
        $this->expectException(InvalidQuestionException::class);

        Question::create('S', QuestionType::SHORT_ANSWER, new Points(1), null, []);
    }

    public function test_open_ended_drops_correct_answer(): void
    {
        $q = Question::create('S', QuestionType::OPEN_ENDED, new Points(10), 'ortiqcha', []);

        $this->assertNull($q->correctAnswer());
    }

    public function test_total_points_is_sum_of_questions(): void
    {
        $this->assertSame(10, $this->quiz([$this->single('a'), $this->single('b')])->totalPoints());
    }

    public function test_file_task_drops_questions_and_quiz_drops_extensions(): void
    {
        $ext = new AllowedFileExtensions([FileExtensions::PDF]);
        $task = Task::create(new LessonId(1), TaskType::FILE, $ext, [$this->single()]);

        $this->assertSame([], $task->questions());

        $quiz = Task::create(new LessonId(1), TaskType::QUIZ, $ext, [$this->single()]);

        $this->assertNull($quiz->allowedExtensions());
    }

    public function test_structure_is_locked_when_submissions_exist(): void
    {
        $task = $this->quiz([$this->single('a')]);

        $this->expectException(TaskLockedException::class);

        $task->revise(TaskType::QUIZ, null, [$this->single('boshqa')], true);
    }
}