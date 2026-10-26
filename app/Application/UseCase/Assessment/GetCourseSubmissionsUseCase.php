<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\Submission\SubmissionStatus;
use App\Domain\Assessment\Submission\TaskSubmission;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;
use App\Domain\Course\CourseId;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Student\Student;
use App\Domain\Student\StudentRepositoryInterface;

final class GetCourseSubmissionsUseCase
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
        private readonly TaskSubmissionRepositoryInterface $submissions,
        private readonly LessonRepositoryInterface $lessons,
        private readonly StudentRepositoryInterface $students
    ) {}

    public function execute(int $courseId): array
    {
        $courseId = new CourseId($courseId);

        $tasks = $this->tasks->findByCourse($courseId)->keyBy(fn(Task $task) => $task->id()->value());
        if($tasks->isEmpty()){
            return [];
        }

        $submissions = $this->submissions->findByTaskIds($tasks->map(fn (Task $task) => $task->id())->values()->all());
        $lessons = $this->lessons->findByCourse($courseId);
        $lessonsById = [];
        foreach($lessons as $lesson){
            $lessonsById[$lesson->id()->value()] = $lesson;
        }
        $students = $this->students
            ->findByIds($submissions->map(fn (TaskSubmission $submission) => $submission->studentId())->unique(fn ($id) => $id->value())->values()->all())
            ->keyBy(fn (Student $student) => $student->id()->value());

        return $submissions
            ->filter(fn (TaskSubmission $submission) => $students->has($submission->studentId()->value()))
            ->map(function (TaskSubmission $submission) use ($tasks, $lessonsById, $students){
                $task = $tasks[$submission->taskId()->value()];

                return new SubmissionRow(
                    $submission,
                    $task,
                    $lessonsById[$task->lessonId()->value()],
                    $students[$submission->studentId()->value()]
                );
            })
            ->sortBy(fn (SubmissionRow $row) => $row->submission->status() === SubmissionStatus::PENDING ? 0 : 1)
            ->values()
            ->all();
    }
}