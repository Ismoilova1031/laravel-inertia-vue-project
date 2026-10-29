<?php

namespace App\Application\UseCase\Certificate;

use App\Application\UseCase\Notification\Notifier;
use App\Application\UseCase\Progress\CourseCompletion;
use App\Domain\Assessment\Submission\SubmissionStatus;
use App\Domain\Assessment\Submission\TaskSubmission;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Assessment\TaskType;
use App\Domain\Certificate\Certificate;
use App\Domain\Certificate\CertificateRepositoryInterface;
use App\Domain\Certificate\CoursePerformance;
use App\Domain\Certificate\Exception\CertificateRuleException;
use App\Domain\Certificate\NewCertificate;
use App\Domain\Course\VO\CourseId;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exception\EnrollmentNotFoundException;
use App\Domain\Notification\NotificationType;
use App\Domain\Student\VO\StudentId;
use DateTimeImmutable;

final class IssueCertificateUseCase
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly CertificateRepositoryInterface $certificates,
        private readonly CourseCompletion $completion,
        private readonly TaskRepositoryInterface $tasks,
        private readonly TaskSubmissionRepositoryInterface $submissions,
        private readonly Notifier $notifier,
    ) {}

    public function execute(int $studentId, int $courseId): Certificate
    {
        $studentId = new StudentId($studentId);
        $courseId = new CourseId($courseId);

        $this->enrollments->findByCourseAndStudent($courseId, $studentId)
            ?? throw EnrollmentNotFoundException::create();

        $existing = $this->certificates->findByCourseAndStudent($courseId, $studentId);
        if ($existing !== null) {
            return $existing;
        }

        if (!$this->completion->progress($studentId, $courseId)->isComplete()) {
            throw CertificateRuleException::because('Sertifikat olish uchun kursni to\'liq tugating.');
        }

        $tasks = $this->tasks->findByCourse($courseId);
        $byTask = $this->submissions
            ->findByStudentAndTaskIds($studentId, $tasks->map(fn (Task $t) => $t->id())->values()->all())
            ->groupBy(fn (TaskSubmission $s) => $s->taskId()->value())
            ->map(fn ($group) => $group->values()->all())
            ->all();

        foreach ($tasks as $task) {
            if ($task->type() !== TaskType::QUIZ) {
                continue;
            }
            foreach ($byTask[$task->id()->value()] ?? [] as $submission) {
                if ($submission->status() === SubmissionStatus::PENDING) {
                    throw CertificateRuleException::because('Ochiq savollaringiz o\'qituvchi tomonidan baholanishini kuting.');
                }
            }
        }

        $now = new DateTimeImmutable();
        $certificate = $this->certificates->create(
            NewCertificate::issue($courseId, $studentId, CoursePerformance::percent($tasks->values()->all(), $byTask), $now)
        );

        $this->notifier->notify(
            $studentId, $courseId, NotificationType::CERTIFICATE_ISSUED,
            'Your certificate is ready',
            $certificate->grade() ? "Grade: {$certificate->grade()->label()}" : null,
        );

        return $certificate;
    }
}