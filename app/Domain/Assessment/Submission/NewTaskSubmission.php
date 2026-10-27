<?php

namespace App\Domain\Assessment\Submission;

use App\Domain\Assessment\Exception\SubmissionRuleException;
use App\Domain\Assessment\Submission\VO\AttemptNumber;
use App\Domain\Assessment\Question;
use App\Domain\Assessment\QuestionType;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\VO\TaskId;
use App\Domain\Assessment\TaskType;
use App\Domain\Student\VO\StudentId;
use DateTimeImmutable;

final class NewTaskSubmission
{
    private function __construct(
        public readonly TaskId $taskId,
        public readonly StudentId $studentId,
        public readonly AttemptNumber $attempt,
        public readonly ?string $content,
        public readonly ?string $filePath,
        public readonly SubmissionStatus $status,
        public readonly ?int $score,
        public readonly DateTimeImmutable $submittedAt,
        public readonly array $answers,
    ) {}

    public static function quiz(
        Task $task,
        StudentId $studentId,
        AttemptNumber $attempt,
        array $given,
        DateTimeImmutable $now,
    ): self {
        self::assertType($task, TaskType::QUIZ);

        $questions = [];
        foreach ($task->questions() as $question) {
            $questions[$question->id()] = $question;
        }

        foreach (array_keys($given) as $questionId) {
            if (! isset($questions[$questionId])) {
                throw SubmissionRuleException::because('Bu topshiriqda bunda savol mavjud emas');
            }
        }

        $answers = [];
        foreach ($questions as $questionId => $question) {
            $answers[] = self::answer($question, $given[$questionId] ?? []);
        }

        $result = QuizResult::of($answers);

        return new self(
            $task->id(),
            $studentId,
            $attempt,
            null,
            null,
            $result->status,
            $result->score,
            $now,
            $answers,
        );
    }

    public static function file(
        Task $task,
        StudentId $studentId,
        AttemptNumber $attempt,
        string $filePath,
        string $extension,
        DateTimeImmutable $now,
    ): self {
        self::assertType($task, TaskType::FILE);

        if (! $task->acceptsExtension($extension)) {
            throw SubmissionRuleException::because('Bu fayl turi ruxsat etilmagan.');
        }

        return new self(
            $task->id(),
            $studentId,
            $attempt,
            null,
            $filePath,
            SubmissionStatus::PENDING,
            null,
            $now,
            [],
        );
    }

    public static function discussion(
        Task $task,
        StudentId $studentId,
        AttemptNumber $attempt,
        string $text,
        DateTimeImmutable $now,
    ): self {
        self::assertType($task, TaskType::DISCUSSION);

        $text = trim($text);
        if ($text === '') {
            throw SubmissionRuleException::because('Matn bo\'sh bo\'lishi mumkin emas.');
        }

        return new self(
            $task->id(),
            $studentId,
            $attempt,
            $text,
            null,
            SubmissionStatus::ACCEPTED,
            null,
            $now,
            [],
        );
    }

    private static function answer(Question $question, array $given): StudentAnswer
    {
        $ids = array_values(array_unique(array_map('intval', $given['options'] ?? [])));
        $text = isset($given['text']) ? trim((string) $given['text']) : null;
        $text = $text === '' ? null : $text;

        if (array_diff($ids, $question->optionIds()) !== []) {
            throw SubmissionRuleException::because('Tanlangan variant bu savolga tegishli emas');
        }

        if ($question->type() === QuestionType::SINGLE_CHOICE && count($ids) > 1) {
            throw SubmissionRuleException::because('Bitta javobli savolga bir nechta javob berib bo\'lmaydi');
        }

        $points = $question->points()->value();

        $score = match ($question->type()) {
            QuestionType::SINGLE_CHOICE, QuestionType::MULTIPLE_SELECT => self::sameSet($ids, $question->correctOptionIds()) ? $points : 0,
            QuestionType::SHORT_ANSWER => $text !== null && self::normalize($text) === self::normalize((string) $question->correctAnswer()) ? $points : 0,
            QuestionType::OPEN_ENDED => $text === null ? 0 : null,
        };

        return new StudentAnswer($question->id(), $ids, $text, $score);
    }

    private static function sameSet(array $a, array $b): bool
    {
        sort($a);
        sort($b);

        return $a === $b;
    }

    private static function normalize(string $value): string
    {
        return mb_strtolower(trim((string) preg_replace('/\s+/', ' ', $value)));
    }

    private static function assertType(Task $task, TaskType $expected): void
    {
        if ($task->type() !== $expected) {
            throw SubmissionRuleException::because('Topshiriq turi mos emas.');
        }
    }
}
