<?php

// tests/Unit/Domain/Assessment/TaskSubmissionTest.php
declare(strict_types=1);

namespace Tests\Unit\Domain\Assessment;

use App\Domain\Assessment\AllowedFileExtensions;
use App\Domain\Assessment\Exception\SubmissionRuleException;
use App\Domain\Assessment\FileExtensions;
use App\Domain\Assessment\VO\Points;
use App\Domain\Assessment\Question;
use App\Domain\Assessment\QuestionOption;
use App\Domain\Assessment\QuestionType;
use App\Domain\Assessment\Submission\VO\AttemptNumber;
use App\Domain\Assessment\Submission\NewTaskSubmission;
use App\Domain\Assessment\Submission\SubmissionAttempts;
use App\Domain\Assessment\Submission\VO\SubmissionId;
use App\Domain\Assessment\Submission\SubmissionStatus;
use App\Domain\Assessment\Submission\TaskSubmission;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\VO\TaskId;
use App\Domain\Assessment\TaskType;
use App\Domain\Lesson\VO\LessonId;
use App\Domain\Student\VO\StudentId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class TaskSubmissionTest extends TestCase
{
    private function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('2026-10-19');
    }

    private function quizTask(): Task
    {
        return Task::reconstitute(new TaskId(1), new LessonId(1), TaskType::QUIZ, null, [
            Question::reconstitute(1, 'Multi', QuestionType::MULTIPLE_SELECT, new Points(4), null, [
                new QuestionOption(11, 'A', true), new QuestionOption(12, 'B', true), new QuestionOption(13, 'C', false),
            ]),
            Question::reconstitute(2, 'Short', QuestionType::SHORT_ANSWER, new Points(2), 'Laravel', []),
            Question::reconstitute(3, 'Open', QuestionType::OPEN_ENDED, new Points(10), null, []),
        ]);
    }

    private function fileTask(): Task
    {
        return Task::reconstitute(
            new TaskId(2), new LessonId(2), TaskType::FILE,
            new AllowedFileExtensions([FileExtensions::PDF]), [],
        );
    }

    private function newQuiz(array $given, int $attempt = 1): NewTaskSubmission
    {
        return NewTaskSubmission::quiz($this->quizTask(), new StudentId(1), new AttemptNumber($attempt), $given, $this->now());
    }

    /** Saqlangan holatni taqlid qiladi (repository id beradi). */
    private function saved(NewTaskSubmission $n, int $id = 1): TaskSubmission
    {
        return TaskSubmission::reconstitute(
            new SubmissionId($id), $n->taskId, $n->studentId, $n->attempt, $n->content, $n->filePath,
            $n->status, $n->score, null, $n->submittedAt, $n->answers,
        );
    }

    private function newFile(string $ext = 'pdf', int $attempt = 1): NewTaskSubmission
    {
        return NewTaskSubmission::file(
            $this->fileTask(), new StudentId(1), new AttemptNumber($attempt), "a.$ext", $ext, $this->now(),
        );
    }

    public function test_multiple_select_is_all_or_nothing(): void
    {
        $full = $this->newQuiz([1 => ['options' => [12, 11]]]);
        $partial = $this->newQuiz([1 => ['options' => [11]]]);
        $extra = $this->newQuiz([1 => ['options' => [11, 12, 13]]]);

        $this->assertSame(4, $full->answers[0]->score());
        $this->assertSame(0, $partial->answers[0]->score());
        $this->assertSame(0, $extra->answers[0]->score());
    }

    public function test_short_answer_ignores_case_and_spaces(): void
    {
        $this->assertSame(2, $this->newQuiz([2 => ['text' => '  laravel ']])->answers[1]->score());
    }

    public function test_open_ended_keeps_submission_pending_until_graded(): void
    {
        $new = $this->newQuiz([1 => ['options' => [11, 12]], 2 => ['text' => 'Laravel'], 3 => ['text' => 'Uzun javob']]);

        $this->assertSame(SubmissionStatus::PENDING, $new->status);
        $this->assertNull($new->score);

        $s = $this->saved($new);
        $s->gradeAnswer($this->quizTask(), 3, 7, 'Yaxshi');

        $this->assertSame(SubmissionStatus::ACCEPTED, $s->status());
        $this->assertSame(13, $s->score());
    }

    public function test_grade_cannot_exceed_points(): void
    {
        $s = $this->saved($this->newQuiz([3 => ['text' => 'Javob']]));

        $this->expectException(SubmissionRuleException::class);

        $s->gradeAnswer($this->quizTask(), 3, 11, null);
    }

    public function test_option_of_another_question_is_rejected(): void
    {
        $this->expectException(SubmissionRuleException::class);

        $this->newQuiz([1 => ['options' => [999]]]);
    }

    public function test_file_with_wrong_extension_is_rejected(): void
    {
        $this->expectException(SubmissionRuleException::class);

        $this->newFile('exe');
    }

    public function test_rejecting_file_requires_comment(): void
    {
        $s = $this->saved($this->newFile());

        $this->expectException(SubmissionRuleException::class);

        $s->review(false, '  ');
    }

    public function test_file_review_accepts_and_cannot_be_repeated(): void
    {
        $s = $this->saved($this->newFile());
        $this->assertSame(SubmissionStatus::PENDING, $s->status());

        $s->review(true, null);
        $this->assertSame(SubmissionStatus::ACCEPTED, $s->status());

        $this->expectException(SubmissionRuleException::class);
        $s->review(false, 'Kech');
    }

    public function test_discussion_is_accepted_immediately_and_rejects_empty_text(): void
    {
        $task = Task::reconstitute(new TaskId(3), new LessonId(3), TaskType::DISCUSSION, null, []);

        $new = NewTaskSubmission::discussion($task, new StudentId(1), new AttemptNumber(1), 'Fikrim', $this->now());
        $this->assertSame(SubmissionStatus::ACCEPTED, $new->status);

        $this->expectException(SubmissionRuleException::class);
        NewTaskSubmission::discussion($task, new StudentId(1), new AttemptNumber(1), '  ', $this->now());
    }

    public function test_fourth_attempt_is_rejected(): void
    {
        $existing = array_map(fn (int $n) => $this->saved($this->newQuiz([], $n), $n), [1, 2, 3]);

        $this->expectException(SubmissionRuleException::class);

        SubmissionAttempts::next($this->quizTask(), $existing);
    }

    public function test_file_cannot_be_resubmitted_while_pending(): void
    {
        $this->expectException(SubmissionRuleException::class);

        SubmissionAttempts::next($this->fileTask(), [$this->saved($this->newFile())]);
    }

    public function test_rejected_file_can_be_resubmitted(): void
    {
        $s = $this->saved($this->newFile());
        $s->review(false, 'Bo\'sh fayl');

        $this->assertSame(2, SubmissionAttempts::next($this->fileTask(), [$s])->value());
    }
}
