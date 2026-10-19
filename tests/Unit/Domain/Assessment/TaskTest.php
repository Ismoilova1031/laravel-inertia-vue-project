<?php

namespace Tests\Unit\Domain\Assessment;

use App\Domain\Assessment\AllowedFileExtensions;
use App\Domain\Assessment\Exception\InvalidQuestionException;
use App\Domain\Assessment\Exception\InvalidTaskException;
use App\Domain\Assessment\Exception\TaskLockedException;
use App\Domain\Assessment\FileExtensions;
use App\Domain\Assessment\NewQuestion;
use App\Domain\Assessment\NewQuestionOption;
use App\Domain\Assessment\Points;
use App\Domain\Assessment\Question;
use App\Domain\Assessment\QuestionOption;
use App\Domain\Assessment\QuestionType;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskContent;
use App\Domain\Assessment\TaskId;
use App\Domain\Assessment\TaskType;
use App\Domain\Lesson\LessonId;
use PHPUnit\Framework\TestCase;

final class TaskTest extends TestCase
{
    private function newSingle(string $text = 'Savol'): NewQuestion
    {
        return NewQuestion::create($text, QuestionType::SINGLE_CHOICE, new Points(5), null, [
            new NewQuestionOption('A', true),
            new NewQuestionOption('B', false),
        ]);
    }

    private function savedSingle(string $text = 'Savol'): Question
    {
        return Question::reconstitute(1, $text, QuestionType::SINGLE_CHOICE, new Points(5), null, [
            new QuestionOption(1, 'A', true),
            new QuestionOption(2, 'B', false),
        ]);
    }

    private function quizTask(string $text = 'Savol'): Task
    {
        return Task::reconstitute(new TaskId(1), new LessonId(1), TaskType::QUIZ, null, [$this->savedSingle($text)]);
    }

    public function test_quiz_requires_a_question(): void
    {
        $this->expectException(InvalidTaskException::class);

        TaskContent::of(TaskType::QUIZ, null, []);
    }

    public function test_file_task_requires_extensions(): void
    {
        $this->expectException(InvalidTaskException::class);

        TaskContent::of(TaskType::FILE, null, []);
    }

    public function test_single_choice_needs_exactly_one_correct_option(): void
    {
        $this->expectException(InvalidQuestionException::class);

        NewQuestion::create('S', QuestionType::SINGLE_CHOICE, new Points(1), null, [
            new NewQuestionOption('A', true),
            new NewQuestionOption('B', true),
        ]);
    }

    public function test_short_answer_requires_correct_answer(): void
    {
        $this->expectException(InvalidQuestionException::class);

        NewQuestion::create('S', QuestionType::SHORT_ANSWER, new Points(1), null, []);
    }

    public function test_open_ended_drops_correct_answer(): void
    {
        $q = NewQuestion::create('S', QuestionType::OPEN_ENDED, new Points(10), 'ortiqcha', []);

        $this->assertNull($q->correctAnswer());
    }

    public function test_total_points_is_sum_of_questions(): void
    {
        $task = Task::reconstitute(new TaskId(1), new LessonId(1), TaskType::QUIZ, null, [
            $this->savedSingle('a'), $this->savedSingle('b'),
        ]);

        $this->assertSame(10, $task->totalPoints());
    }

    public function test_content_drops_irrelevant_parts_by_type(): void
    {
        $ext = new AllowedFileExtensions([FileExtensions::PDF]);

        $file = TaskContent::of(TaskType::FILE, $ext, [$this->newSingle()]);
        $this->assertSame([], $file->questions());

        $quiz = TaskContent::of(TaskType::QUIZ, $ext, [$this->newSingle()]);
        $this->assertNull($quiz->allowedExtensions());
    }

    public function test_structure_is_locked_when_submissions_exist(): void
    {
        $task = $this->quizTask('Eski');

        $this->expectException(TaskLockedException::class);

        $task->revise(TaskContent::of(TaskType::QUIZ, null, [$this->newSingle('Yangi')]), true);
    }

    public function test_same_structure_is_not_replaced_even_when_locked(): void
    {
        $task = $this->quizTask('Savol');

        $task->revise(TaskContent::of(TaskType::QUIZ, null, [$this->newSingle('Savol')]), true);

        $this->assertNull($task->questionReplacement());
    }

    public function test_changed_structure_is_queued_for_replacement_without_submissions(): void
    {
        $task = $this->quizTask('Eski');

        $task->revise(TaskContent::of(TaskType::QUIZ, null, [$this->newSingle('Yangi')]), false);

        $this->assertCount(1, $task->questionReplacement());
    }

    public function test_extensions_can_change_when_locked(): void
    {
        $task = Task::reconstitute(
            new TaskId(1), new LessonId(1), TaskType::FILE,
            new AllowedFileExtensions([FileExtensions::PDF]), [],
        );

        $task->revise(TaskContent::of(TaskType::FILE, new AllowedFileExtensions([FileExtensions::DOCX]), []), true);

        $this->assertSame(['docx'], $task->allowedExtensions()->values());
        $this->assertNull($task->questionReplacement());
    }
}
