<?php

namespace App\Application\UseCase\Certificate;

use App\Domain\Certificate\Certificate;
use App\Domain\Course\Course;
use App\Domain\Student\Student;

final class CertificateView
{
    public function __construct(
        public readonly Certificate $certificate,
        public readonly Course $course,
        public readonly Student $student,
    ) {}
}