<?php

namespace App\Domain\Assessment\Submission;

use App\Domain\Assessment\Exception\SubmissionRuleException;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskId;
use App\Domain\Student\StudentId;
use DateTimeImmutable;

final class TaskSubmission
{
    private function __construct(
        private readonly SubmissionId $id,
        private readonly TaskId $taskId,
        private readonly StudentId $studentId,
        private readonly AttemptNumber $attempt,
        private readonly ?string $content,
        private readonly ?string $filePath,
        private SubmissionStatus $status,
        private ?int $score,
        private ?string $teacherComment,
        private readonly DateTimeImmutable $submittedAt,
        private array $answers,
    ) {}

    public static function reconstitute(
        SubmissionId $id,
        TaskId $taskId,
        StudentId $studentId,
        AttemptNumber $attempt,
        ?string $content,
        ?string $filePath,
        SubmissionStatus $status,
        ?int $score,
        ?string $teacherComment,
        DateTimeImmutable $submittedAt,
        array $answers,
    ): self {
        return new self(
            $id,
            $taskId,
            $studentId,
            $attempt,
            $content,
            $filePath,
            $status,
            $score,
            $teacherComment,
            $submittedAt,
            array_values($answers),
        );
    }

    public function review(bool $accept, ?string $comment): void
    {
        if ($this->filePath === null) {
            throw SubmissionRuleException::because('Faqat fayl topshiriqlari ko\'rib chiqiladi.');
        }

        if ($this->status !== SubmissionStatus::PENDING) {
            throw SubmissionRuleException::because('Bu topshiriq allaqachon ko\'rib chiqilgan.');
        }

        $comment = $comment !== null ? trim($comment) : null;
        if (!$accept && ($comment === null || $comment === '')) {
            throw SubmissionRuleException::because('Rad etish uchun izoh kiritilishi shart.');
        }

        $this->status = $accept ? SubmissionStatus::ACCEPTED : SubmissionStatus::REJECTED;
        $this->teacherComment = $comment === '' ? null : $comment;
    }

    public function gradeAnswer(Task $task, int $questionId, int $score, ?string $comment): void
    {
        $question = null;
        foreach ($task->questions() as $q) {
            if ($q->id() === $questionId) {
                $question = $q;
            }
        }
        if ($question === null) {
            throw SubmissionRuleException::because('Bu topshiriqda bunday savol mavjud emas.');
        }

        if($score < 0 || $score > $question->points()->value()) {
            throw SubmissionRuleException::because("Ball 0 dan {$question->points()->value()} gacha bo'lishi kerak.");
        }

        foreach($this->answers as $answer){
            if($answer->questionId() === $questionId){
                $answer->grade($score, $comment);

                $result = QuizResult::of($this->answers);
                $this->status = $result->status;
                $this->score = $result->score;

                return;
            }
        }

        throw SubmissionRuleException::because('Bu savolga javob berilmagan.');
    }

    public function id(): SubmissionId { return $this->id; }
    public function taskId(): TaskId { return $this->taskId; }
    public function studentId(): StudentId { return $this->studentId; }
    public function attempt(): AttemptNumber { return $this->attempt; }
    public function content(): ?string { return $this->content; }
    public function filePath(): ?string { return $this->filePath; }
    public function status(): SubmissionStatus { return $this->status; }
    public function score(): ?int { return $this->score; }
    public function teacherComment(): ?string { return $this->teacherComment; }
    public function submittedAt(): DateTimeImmutable { return $this->submittedAt; }
    public function answers(): array { return $this->answers; }
}
