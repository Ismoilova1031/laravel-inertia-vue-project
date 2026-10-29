<?php

namespace App\Domain\Certificate;

use App\Domain\Course\VO\CourseId;
use App\Domain\Student\VO\StudentId;
use App\Domain\Certificate\VO\CertificateId;
use DateTimeImmutable;

final class Certificate
{
    public function __construct(
        public readonly CertificateId $id,
        public readonly CourseId $courseId,
        public readonly StudentId $studentId,
        public readonly CertificateNumber $number,
        public readonly ?CourseGrade $grade,
        public readonly ?int $percent,
        public readonly DateTimeImmutable $issuedAt,
    ) {}

    public static function reconstitute(
        CertificateId $id,
        CourseId $courseId,
        StudentId $studentId,
        CertificateNumber $number,
        ?CourseGrade $grade,
        ?int $percent,
        DateTimeImmutable $issuedAt
    ): self {
        return new self($id, $courseId, $studentId, $number, $grade, $percent, $issuedAt);
    }

    public function id(): CertificateId { return $this->id; }
    public function courseId(): CourseId { return $this->courseId; }
    public function studentId(): StudentId { return $this->studentId; }
    public function number(): CertificateNumber { return $this->number; }
    public function grade(): ?CourseGrade { return $this->grade; }
    public function percent(): ?int { return $this->percent; }
    public function issuedAt(): DateTimeImmutable { return $this->issuedAt; }
}