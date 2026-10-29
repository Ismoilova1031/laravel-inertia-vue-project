<?php

namespace App\Http\Controllers;

use App\Application\UseCase\Certificate\GetCertificateUseCase;
use App\Application\UseCase\Certificate\IssueCertificateUseCase;
use App\Domain\Certificate\Exception\CertificateNotFoundException;
use App\Domain\Certificate\Exception\CertificateRuleException;
use App\Domain\Enrollment\Exception\EnrollmentNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

final class CertificateController extends Controller
{
    public function __construct(
        private readonly IssueCertificateUseCase $issue,
        private readonly GetCertificateUseCase $get,
    ) {}

    public function issue(int $student, int $course): RedirectResponse
    {
        try {
            $certificate = $this->issue->execute($student, $course);
        } catch (EnrollmentNotFoundException) {
            abort(404);
        } catch (CertificateRuleException $e) {
            throw ValidationException::withMessages(['certificate' => $e->getMessage()]);
        }

        return to_route('certificates.show', ['number' => $certificate->number()->value()]);
    }

    public function show(string $number): Response
    {
        try {
            $view = $this->get->execute($number);
        } catch (CertificateNotFoundException) {
            abort(404);
        }

        $c = $view->certificate;

        return Inertia::render('Certificates/Show', [
            'certificate' => [
                'number' => $c->number()->value(),
                'grade' => $c->grade()?->label(),
                'percent' => $c->percent(),
                'issuedAt' => $c->issuedAt()->format('F j, Y'),
                'studentName' => $view->student->name()->fullName(),
                'courseTitle' => $view->course->title()->value(),
            ],
        ]);
    }
}