<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Assessment\Submission\TaskCompletion;
use App\Domain\Assessment\Submission\TaskSubmission;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonType;
use App\Domain\Progress\LessonProgressRepositoryInterface;
use App\Domain\Student\StudentId;

final class LessonCompletionResolver
{
    public function __construct(
        private readonly LessonProgressRepositoryInterface $progress,
        private readonly TaskRepositoryInterface $tasks,
        private readonly TaskSubmissionRepositoryInterface $submissions,
    ) {}

    /**
     * @param list<Lesson> $lessons
     * @return list<int> bajarilgan dars ID'lari
     */
    public function completedIds(StudentId $studentId, array $lessons): array
    {
        $done = $this->progress->completedLessonIds(
            $studentId,
            array_map(fn(Lesson $l) => $l->id(), $lessons)
        );

        $taskLessons = array_values(array_filter($lessons, fn(Lesson $l) => $l->type() === LessonType::TASK));
        if ($taskLessons === []) {
            return $done;
        }

        $tasks = $this->tasks->findByLessonIds(array_map(fn(Lesson $l) => $l->id(), $taskLessons));
        $submissions = $this->submissions
            ->findByStudentAndTaskIds($studentId, $tasks->map(fn(Task $t) => $t->id())->all())
            ->groupBy(fn(TaskSubmission $s) => $s->taskId()->value());

        foreach ($tasks as $task) {
            $own = $submissions->get($task->id()->value(), collect())->values()->all();

            if (TaskCompletion::isDone($task, $own)) {
                $done[] = $task->lessonId()->value();
            }
        }

        return array_values(array_unique($done));
    }
}
