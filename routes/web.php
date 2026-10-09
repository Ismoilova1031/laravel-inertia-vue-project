<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\StudentController;
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
    
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');