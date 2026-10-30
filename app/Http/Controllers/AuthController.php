<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

final class AuthController extends Controller
{
    public function showLogin(): Response|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('courses.index');
        }
        if (Auth::guard('student')->check()) {
            return redirect()->route('learn.courses', ['student' => Auth::guard('student')->id()]);
        }

        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('courses.index'));
        }

        if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('learn.courses', ['student' => Auth::guard('student')->id()]));
        }

        throw ValidationException::withMessages(['username' => 'Username yoki parol noto\'g\'ri.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}