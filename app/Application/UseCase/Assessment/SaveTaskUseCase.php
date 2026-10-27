<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\AllowedFileExtensions;
use App\Domain\Assessment\NewQuestion;
use App\Domain\Assessment\NewQuestionOption;
use App\Domain\Assessment\NewTask;
use App\Domain\Assessment\VO\Points;
use App\Domain\Assessment\QuestionType;
use App\Domain\Assessment\VO\TaskContent;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;
use App\Domain\Assessment\TaskType;
use App\Domain\Lesson\VO\LessonId;

final class SaveTaskUseCase
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
        private readonly TaskSubmissionRepositoryInterface $submissions,
    ) {}

    public function execute(int $lessonId, TaskData $data): void
    {
        $lesson = new LessonId($lessonId);

        $content = TaskContent::of(
            TaskType::from($data->type),
            $data->fileExtensions ? AllowedFileExtensions::fromStrings($data->fileExtensions) : null,
            $this->buildQuestions($data->questions ?? []),
        );

        $task = $this->tasks->findByLessonId($lesson);

        if ($task === null) {
            $this->tasks->create(NewTask::create($lesson, $content));

            return;
        }

        $task->revise($content, $this->submissions->existsForTask($task->id()));
        $this->tasks->save($task);
    }

    /** @return list<NewQuestion> */
    private function buildQuestions(array $raw): array
    {
        return array_map(fn (array $q) => NewQuestion::create(
            (string) $q['question'],
            QuestionType::from((int) $q['question_type']),
            new Points((int) $q['points']),
            $q['correct_answer'] ?? null,
            array_map(
                fn (array $o) => new NewQuestionOption((string) $o['option'], (bool) $o['is_correct']),
                $q['options'] ?? [],
            ),
        ), array_values($raw));
    }
}
