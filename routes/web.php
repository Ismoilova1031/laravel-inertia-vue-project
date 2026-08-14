<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/courses/{course}', [CourseController::class, 'show'])
    ->name('courses.show');

Route::get('/courses/create', [CourseController::class, 'create'])
    ->name('courses.create');

Route::post('/courses', [CourseController::class, 'store'])
    ->name('courses.store');