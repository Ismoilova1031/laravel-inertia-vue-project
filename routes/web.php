<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('courses', CourseController::class)
    ->only([
        'create',
        'store',
        'show',
        'update',
        'destroy',
        'index',
    ]);

Route::put(
    '/courses/{course}/lessons/reorder',
    [LessonController::class, 'reorder']
)->name('courses.lessons.reorder');

Route::resource('courses.lessons', LessonController::class)
    ->only([
        'create',
        'store',
        'edit',
        'update',
        'destroy',
    ]);