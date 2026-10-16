<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\AllowedFileExtensions;
use App\Domain\Assessment\Points;
use App\Domain\Assessment\Question;
use App\Domain\Assessment\QuestionOption;
use App\Domain\Assessment\QuestionType;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Assessment\TaskType;
use App\Domain\Lesson\LessonId;

final class SaveTaskUseCase
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
        private readonly TaskSubmissionLookupInterface $submissions,
    ) {}

    /** Darsning topshirig'ini yaratadi yoki mavjudini yangilaydi. */
    public function execute(int $lessonId, TaskData $data): void
    {
        $lesson = new LessonId($lessonId);
        $type = TaskType::from($data->type);
        $extensions = $data->fileExtensions
            ? AllowedFileExtensions::fromStrings($data->fileExtensions)
            : null;
        $questions = $this->buildQuestions($data->questions ?? []);

        $task = $this->tasks->findByLessonId($lesson);

        if ($task === null) {
            $task = Task::create($lesson, $type, $extensions, $questions);
        } else {
            $task->revise($type, $extensions, $questions, $this->submissions->exists($task->id()));
        }

        $this->tasks->save($task);
    }

    /** @return list<Question> */
    private function buildQuestions(array $raw): array
    {
        return array_map(fn (array $q) => Question::create(
            (string) $q['question'],
            QuestionType::from((int) $q['question_type']),
            new Points((int) $q['points']),
            $q['correct_answer'] ?? null,
            array_map(
                fn (array $o) => new QuestionOption((string) $o['option'], (bool) $o['is_correct']),
                $q['options'] ?? [],
            ),
        ), array_values($raw));
    }
}