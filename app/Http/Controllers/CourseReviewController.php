<?php

namespace App\Http\Controllers;

use App\Application\UseCase\Review\SubmitReviewUseCase;
use App\Domain\Review\Exception\ReviewNotAllowedException;
use App\Http\Requests\SubmitReviewRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

final class CourseReviewController extends Controller
{
    public function __construct(private readonly SubmitReviewUseCase $submitReview) {}

    public function store(SubmitReviewRequest $request, int $student, int $course): RedirectResponse
    {
        try {
            $this->submitReview->execute($student, $course, (int) $request->input('rating'), $request->input('comment'));
        } catch (ReviewNotAllowedException $e) {
            throw ValidationException::withMessages(['review' => $e->getMessage()]);
        }

        return back();
    }
}