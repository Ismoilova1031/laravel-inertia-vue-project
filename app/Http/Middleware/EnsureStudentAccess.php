<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class EnsureStudentAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::guard('web')->check()){
            return $next($request);
        }

        $student = Auth::guard('student')->user();

        if($student === null){
            return redirect()->guest(route('login'));
        }

        if((int) $request->route('student') !== (int) $student->getAuthIdentifier()){
            abort(403);
        }

        return $next($request);
    }
}