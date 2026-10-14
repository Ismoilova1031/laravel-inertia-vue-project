<?php

namespace App\Http\Controllers;

use App\Application\UseCase\Student\RegisterStudentUseCase;
use App\Application\UseCase\Student\GetStudentsUseCase;
use App\Domain\Student\Exception\EmailAlreadyTakenException;
use App\Domain\Student\Exception\UsernameAlreadyTakenException;
use App\Http\Requests\RegisterStudentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Resources\StudentListResource;
use App\Application\UseCase\Student\GetStudentUseCase;
use App\Application\UseCase\Student\UpdateStudentUseCase;
use App\Application\UseCase\Student\DeleteStudentUseCase;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Http\Requests\UpdateStudentRequest;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Course\Exception\CourseNotPublishedException;

enum StudentPages: string
{
    case INDEX = 'Students/Index';
    case CREATE = 'Students/Create';
    case EDIT = 'Students/Edit';
}
final class StudentController extends Controller
{
    public function __construct(
        private readonly RegisterStudentUseCase $registerStudent,
        private readonly GetStudentsUseCase $getStudents,
        private readonly GetStudentUseCase $getStudent,
        private readonly UpdateStudentUseCase $updateStudent,
        private readonly DeleteStudentUseCase $deleteStudent,
    ) {}

    public function index(): Response
    {
        return Inertia::render(StudentPages::INDEX, [
            'students' => StudentListResource::collection($this->getStudents->execute())->resolve(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render(StudentPages::CREATE);
    }

    public function store(RegisterStudentRequest $request): RedirectResponse
    {
        try {
            $this->registerStudent->execute($request->toCommand());
        } catch (UsernameAlreadyTakenException) {
            throw ValidationException::withMessages(['username' => 'Bu username band.']);
        } catch (EmailAlreadyTakenException) {
            throw ValidationException::withMessages(['email' => 'Bu email band.']);
        }

        return redirect()->route('students.index');
    }

    public function createForCourse(int $course): Response
    {
        return Inertia::render(StudentPages::CREATE, [
            'course_id' => $course,
        ]);
    }

    public function storeForCourse(RegisterStudentRequest $request, int $course): RedirectResponse
    {
        try {
            $this->registerStudent->execute($request->toCommand(), $course);
        } catch (CourseNotFoundException) {
            abort(404);
        } catch (CourseNotPublishedException) {
            throw ValidationException::withMessages([
                'username' => 'Faqat e\'lon qilingan (Published) kursga student qo\'shish mumkin.',
            ]);
        } catch (UsernameAlreadyTakenException) {
            throw ValidationException::withMessages(['username' => 'Bu username band.']);
        } catch (EmailAlreadyTakenException) {
            throw ValidationException::withMessages(['email' => 'Bu email band.']);
        }

        return redirect()->route('courses.show', $course);
    }

    public function edit(int $student): Response
    {
        try {
            $item = $this->getStudent->execute($student);
        } catch (StudentNotFoundException) {
            abort(404);
        }

        return Inertia::render(StudentPages::EDIT, [
            'student' => (new StudentListResource($item))->resolve(),
        ]);
    }

    public function update(UpdateStudentRequest $request, int $student): RedirectResponse
    {
        try {
            $this->updateStudent->execute($request->toCommand());
        } catch (StudentNotFoundException) {
            abort(404);
        } catch (UsernameAlreadyTakenException) {
            throw ValidationException::withMessages(['username' => 'Bu username band.']);
        } catch (EmailAlreadyTakenException) {
            throw ValidationException::withMessages(['email' => 'Bu email band.']);
        }

        return redirect()->route('students.index');
    }

    public function destroy(int $student): RedirectResponse
    {
        try {
            $this->deleteStudent->execute($student);
        } catch (StudentNotFoundException) {
            abort(404);
        }

        return redirect()->route('students.index');
    }
}
