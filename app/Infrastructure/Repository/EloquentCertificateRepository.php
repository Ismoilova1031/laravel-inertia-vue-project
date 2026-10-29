<?php

namespace App\Infrastructure\Repository;

use App\Domain\Certificate\Certificate;
use App\Domain\Certificate\VO\CertificateId;
use App\Domain\Certificate\CertificateNumber;
use App\Domain\Certificate\CertificateRepositoryInterface;
use App\Domain\Certificate\NewCertificate;
use App\Domain\Course\VO\CourseId;
use App\Domain\Student\VO\StudentId;
use App\Infrastructure\Eloquent\CertificateModel;

final class EloquentCertificateRepository implements CertificateRepositoryInterface
{
    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?Certificate
    {
        $model = CertificateModel::where('course_id', $courseId->value())
            ->where('student_id', $studentId->value())->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function findByNumber(CertificateNumber $number): ?Certificate
    {
        $model = CertificateModel::where('number', $number->value())->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function create(NewCertificate $new): Certificate
    {
        return $this->toEntity(CertificateModel::create([
            'course_id' => $new->courseId->value(),
            'student_id' => $new->studentId->value(),
            'number' => $new->number->value(),
            'grade' => $new->grade,
            'percent' => $new->percent,
            'issued_at' => $new->issuedAt,
        ]));
    }

    private function toEntity(CertificateModel $model): Certificate
    {
        return Certificate::reconstitute(
            new CertificateId((int) $model->id),
            new CourseId((int) $model->course_id),
            new StudentId((int) $model->student_id),
            new CertificateNumber($model->number),
            $model->grade,
            $model->oercent !== null ? (int) $model->percent : null,
            $model->issued_at,
        );
    }
}