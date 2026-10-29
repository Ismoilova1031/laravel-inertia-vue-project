<?php

namespace App\Domain\Certificate;

use App\Domain\Course\VO\CourseId;
use App\Domain\Student\VO\StudentId;

interface CertificateRepositoryInterface
{
    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?Certificate;

    public function findByNumber(CertificateNumber $number): ?Certificate;

    public function create(NewCertificate $new): Certificate;
}