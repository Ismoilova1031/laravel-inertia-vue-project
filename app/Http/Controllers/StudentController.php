<?php

namespace App\Http\Controllers;

use App\Application\Student\RegisterStudent\RegisterStudentUseCase;
use App\Application\Student\GetStudents\GetStudentsUseCase;
use App\Domain\Student\Exception\EmailAlreadyTakenException;
use App\Domain\Student\Exception\UsernameAlreadyTakenException;
use App\Http\Requests\RegisterStudentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Resources\StudentListResource;

final class StudentController extends Controller
{
    public function __construct(
        private readonly RegisterStudentUseCase $registerStudent,
        private readonly GetStudentsUseCase $getStudents,
    ) {}

    public function index(): Response
    {
        return Inertia::render('Students/Index', [
            'students' => StudentListResource::collection($this->getStudents->execute())->resolve(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Students/Create');
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
}