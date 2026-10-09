<?php

namespace App\Http\Controllers;

use App\Application\Student\RegisterStudent\RegisterStudentUseCase;
use App\Domain\Student\Exception\EmailAlreadyTakenException;
use App\Domain\Student\Exception\UsernameAlreadyTakenException;
use App\Http\Requests\RegisterStudentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

final class StudentController extends Controller
{
    public function __construct(private readonly RegisterStudentUseCase $registerStudent) {}

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