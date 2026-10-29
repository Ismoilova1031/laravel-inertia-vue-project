<?php

namespace App\Application\UseCase\Certificate;

use App\Domain\Certificate\CertificateNumber;
use App\Domain\Certificate\CertificateRepositoryInterface;
use App\Domain\Certificate\Exception\CertificateNotFoundException;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Student\StudentRepositoryInterface;
use InvalidArgumentException;

final class GetCertificateUseCase
{
    public function __construct(
        private readonly CertificateRepositoryInterface $certificates,
        private readonly CourseRepositoryInterface $courses,
        private readonly StudentRepositoryInterface $students,
    ) {}

    public function execute(string $number): CertificateView
    {
        try {
            $certificate = $this->certificates->findByNumber(new CertificateNumber($number));
        } catch (InvalidArgumentException) {
            $certificate = null;
        }

        $certificate ?? throw CertificateNotFoundException::create();

        $course = $this->courses->findById($certificate->courseId());
        $student = $this->students->findById($certificate->studentId());

        if ($course === null || $student === null) {
            throw CertificateNotFoundException::create();
        }

        return new CertificateView($certificate, $course, $student);
    }
}