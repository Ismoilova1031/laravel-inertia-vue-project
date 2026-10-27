<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Assessment\Exception\SubmissionRuleException;
use App\Domain\Assessment\Submission\VO\AttemptNumber;
use App\Domain\Assessment\Submission\SubmissionAttempts;
use App\Domain\Assessment\Submission\TaskCompletion;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exception\StudentNotActiveInCourseException;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\VO\LessonId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Lesson\LessonType;
use App\Domain\Progress\LessonProgressRepositoryInterface;
use App\Domain\Student\VO\StudentId;

final class GetStudentLessonUseCase
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly CourseRepositoryInterface $courses,
        private readonly LessonRepositoryInterface $lessons,
        private readonly LessonProgressRepositoryInterface $progress,
        private readonly TaskRepositoryInterface $tasks,
        private readonly TaskSubmissionRepositoryInterface $submissions,
    ) {}

    public function execute(int $studentId, int $lessonId): StudentLessonView
    {
        $studentId = new StudentId($studentId);
        $lessonId = new LessonId($lessonId);

        $lesson = $this->lessons->findById($lessonId) ?? throw LessonNotFoundException::withId($lessonId);

        $enrollment = $this->enrollments->findByCourseAndStudent($lesson->courseId(), $studentId);
        if ($enrollment === null || !$enrollment->isActive()) {
            throw StudentNotActiveInCourseException::create();
        }

        $course = $this->courses->findById($lesson->courseId())
            ?? throw CourseNotFoundException::withId($lesson->courseId());

        $siblings = $this->lessons->findByCourse($lesson->courseId());
        $index = array_search($lessonId->value(), array_map(fn (Lesson $l) => $l->id()->value(), $siblings), true);

        $task = $lesson->type() === LessonType::TASK ? $this->taskView($lessonId, $studentId) : null;

        $completed = $lesson->type() === LessonType::TASK
            ? ($task !== null && TaskCompletion::isDone($task->task, $task->submissions))
            : $this->progress->findByLessonAndStudent($lessonId, $studentId) !== null;

        return new StudentLessonView(
            $course, $lesson, $completed,
            $siblings[$index - 1] ?? null,
            $siblings[$index + 1] ?? null,
            $task,
        );
    }

    private function taskView(LessonId $lessonId, StudentId $studentId): ?StudentTaskView
    {
        $task = $this->tasks->findByLessonId($lessonId);
        if ($task === null) {
            return null;
        }

        $submissions = $this->submissions->findByTaskAndStudent($task->id(), $studentId);

        try {
            SubmissionAttempts::next($task, $submissions);
            $canSubmit = true;
            $reason = null;
        } catch (SubmissionRuleException $e) {
            $canSubmit = false;
            $reason = $e->getMessage();
        }

        return new StudentTaskView(
            $task,
            $submissions,
            $canSubmit,
            $reason,
            max(0, AttemptNumber::MAX - count($submissions)),
            TaskCompletion::bestScore($submissions),
            $task->totalPoints(),
            count($submissions) >= AttemptNumber::MAX,
        );
    }
}