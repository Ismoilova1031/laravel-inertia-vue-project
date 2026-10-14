<?php

namespace App\Http\Controllers;

use App\Application\UseCase\Enrollment\DeactivateEnrollmentCommand;
use App\Application\UseCase\Enrollment\DeactivateEnrollmentUseCase;
use App\Application\UseCase\Enrollment\EnrollStudentUseCase;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Course\Exception\CourseNotPublishedException;
use App\Domain\Enrollment\Exception\AlreadyEnrolledException;
use App\Domain\Enrollment\Exception\EnrollmentNotActiveException;
use App\Domain\Enrollment\Exception\EnrollmentNotFoundException;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Http\Requests\EnrollStudentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

final class EnrollmentController extends Controller
{
    public function __construct(
        private readonly EnrollStudentUseCase $enrollStudent,
        private readonly DeactivateEnrollmentUseCase $deactivateEnrollment,
    ) {}

    public function store(EnrollStudentRequest $request, int $course): RedirectResponse
    {
        try {
            $this->enrollStudent->execute($request->toCommand());
        } catch (CourseNotFoundException|StudentNotFoundException) {
            abort(404);
        } catch (CourseNotPublishedException) {
            throw ValidationException::withMessages([
                'student_id' => 'Faqat e\'lon qilingan (Published) kursga student yozish mumkin.',
            ]);
        } catch (AlreadyEnrolledException) {
            throw ValidationException::withMessages([
                'student_id' => 'Bu student kursga allaqachon yozilgan.',
            ]);
        }

        return redirect()->route('courses.show', $course);
    }

    public function destroy(int $course, int $student): RedirectResponse
    {
        try {
            $this->deactivateEnrollment->execute(new DeactivateEnrollmentCommand(
                courseId: $course,
                studentId: $student,
            ));
        } catch (EnrollmentNotFoundException|EnrollmentNotActiveException) {
            abort(404);
        }

        return redirect()->route('courses.show', $course);
    }
}