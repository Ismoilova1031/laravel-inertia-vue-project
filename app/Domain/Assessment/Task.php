<?php

namespace App\Domain\Assessment;

use App\Domain\Assessment\Exception\InvalidTaskException;
use App\Domain\Assessment\Exception\TaskLockedException;
use App\Domain\Lesson\LessonId;

final class Task
{
    private TaskType $type;
    private ?AllowedFileExtensions $allowedExtensions = null;
    /** @var list<Question> */
    private array $questions = [];
    private bool $questionsChanged = false;

    private function __construct(
        private ?TaskId $id,
        private readonly LessonId $lessonId,
    ) {}

    public static function create(
        LessonId $lessonId,
        TaskType $type,
        ?AllowedFileExtensions $extensions,
        array $questions,
    ): self {
        $task = new self(null, $lessonId);
        $task->applyContent($type, $extensions, $questions);
        $task->questionsChanged = true;

        return $task;
    }

    public static function reconstitute(
        TaskId $id,
        LessonId $lessonId,
        TaskType $type,
        ?AllowedFileExtensions $extensions,
        array $questions,
    ): self {
        $task = new self($id, $lessonId);
        $task->type = $type;
        $task->allowedExtensions = $extensions;
        $task->questions = array_values($questions);

        return $task;
    }

    public function revise(
        TaskType $type,
        ?AllowedFileExtensions $extensions,
        array $questions,
        bool $hasSubmissions,
    ): void {
        $candidate = clone $this;
        $candidate->applyContent($type, $extensions, $questions);

        $structureChanged = $candidate->type !== $this->type
            || $candidate->signature() !== $this->signature();

        if ($structureChanged && $hasSubmissions) {
            throw TaskLockedException::create();
        }

        $this->allowedExtensions = $candidate->allowedExtensions;

        if ($structureChanged) {
            $this->type = $candidate->type;
            $this->questions = $candidate->questions;
            $this->questionsChanged = true;
        }
    }

    private function applyContent(TaskType $type, ?AllowedFileExtensions $extensions, array $questions): void
    {
        if ($type === TaskType::QUIZ && count($questions) < 1) {
            throw InvalidTaskException::because('Quiz kamida bitta savol talab qiladi.');
        }
        if ($type === TaskType::FILE && $extensions === null) {
            throw InvalidTaskException::because('Fayl topshirig\'i uchun kamida bitta kengaytma tanlang.');
        }

        $this->type = $type;
        $this->allowedExtensions = $type === TaskType::FILE ? $extensions : null;
        $this->questions = $type === TaskType::QUIZ ? array_values($questions) : [];
    }

    private function signature(): array
    {
        return array_map(fn (Question $q) => $q->signature(), $this->questions);
    }

    public function totalPoints(): int
    {
        return array_sum(array_map(fn (Question $q) => $q->points()->value(), $this->questions));
    }

    public function questionsChanged(): bool { return $this->questionsChanged; }

    public function id(): ?TaskId { return $this->id; }
    public function lessonId(): LessonId { return $this->lessonId; }
    public function type(): TaskType { return $this->type; }
    public function allowedExtensions(): ?AllowedFileExtensions { return $this->allowedExtensions; }
    public function questions(): array { return $this->questions; }
}