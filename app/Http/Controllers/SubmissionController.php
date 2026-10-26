<?php

namespace App\Http\Controllers;

use App\Application\UseCase\Assessment\DownloadSubmissionFileUseCase;
use App\Application\UseCase\Assessment\GradeAnswerUseCase;
use App\Application\UseCase\Assessment\ReviewSubmissionUseCase;
use App\Domain\Assessment\Exception\AssessmentRuleViolation;
use App\Domain\Assessment\Exception\SubmissionNotFoundException;
use App\Domain\Assessment\Exception\TaskNotFoundException;
use App\Http\Requests\GradeAnswerRequest;
use App\Http\Requests\ReviewSubmissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class SubmissionController extends Controller
{
    public function __construct(
        private readonly ReviewSubmissionUseCase $review,
        private readonly GradeAnswerUseCase $grade,
        private readonly DownloadSubmissionFileUseCase $download
    ) {}

    public function file(int $submission): StreamedResponse
    {
        try {
            return $this->download->execute($submission);
        } catch (SubmissionNotFoundException) {
            abort(404);
        }
    }

    public function review(ReviewSubmissionRequest $request, int $submission): RedirectResponse
    {
        try {
            $this->review->execute($submission, $request->boolean('accept'), $request->input('comment'));
        } catch (SubmissionNotFoundException) {
            abort(404);
        } catch (AssessmentRuleViolation $error) {
            throw ValidationException::withMessages(['review' => $error->getMessage()]);
        }

        return back();
    }

    public function grade(GradeAnswerRequest $request, int $submission, int $question): RedirectResponse
    {
        try {
            $this->grade->execute($submission, $question, (int) $request->input('score'), $request->input('comment'));
        } catch (SubmissionNotFoundException | TaskNotFoundException) {
            abort(404);
        } catch (AssessmentRuleViolation $error) {
            throw ValidationException::withMessages(['grade' => $error->getMessage()]);
        }

        return back();
    }

    public function preview(int $submission): StreamedResponse
    {
        try {
            return $this->download->execute($submission, inline: true);
        } catch (SubmissionNotFoundException) {
            abort(404);
        }
    }
}
