<?php

namespace App\Domain\Certificate;

use App\Domain\Course\VO\CourseId;
use App\Domain\Student\VO\StudentId;
use DateTimeImmutable;

final class NewCertificate
{
    private function __construct(
        public readonly CourseId $courseId,
        public readonly StudentId $studentId,
        public readonly CertificateNumber $number,
        public readonly ?CourseGrade $grade,
        public readonly ?int $percent,
        public readonly DateTimeImmutable $issuedAt,
    ) {}

    public static function issue(CourseId $courseId, StudentId $studentId, ?int $percent, DateTimeImmutable $now): self
    {
        return new self(
            $courseId, $studentId, CertificateNumber::generate($now),
            $percent !== null ? CourseGrade::fromPercent($percent) : null,
            $percent, $now
        );
    }
}