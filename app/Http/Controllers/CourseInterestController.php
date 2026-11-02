<?php

namespace App\Http\Controllers;

use App\Application\UseCase\CourseInterest\ToggleCourseInterestUseCase;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Course\Exception\CourseNotPublishedException;
use App\Domain\CourseInterest\InterestType;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Http\Requests\ToggleInterestRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

final class CourseInterestController extends Controller
{
    public function __construct(
        private readonly ToggleCourseInterestUseCase $toggle
    ) {}

    public function store(ToggleInterestRequest $request, int $student, int $course): RedirectResponse
    {
        try{
            $this->toggle->execute(
                $student,
                $course,
                InterestType::from($request->validated('type')),
                $request->boolean('enabled'),
            );
        } catch (CourseNotFoundException | StudentNotFoundException) {
            abort(404);
        } catch (CourseNotPublishedException) {
            throw ValidationException::withMessages(['interest' => 'This course is not available anymore.']);
        }

        return back();
    }
}