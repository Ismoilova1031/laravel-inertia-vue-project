<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('courses', CourseController::class)
    ->only([
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy',
    ]);
Route::resource('/courses/{course}/lessons', LessonController::class)
    ->only([
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy',
    ]);
