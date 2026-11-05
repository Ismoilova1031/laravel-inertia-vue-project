<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseInterestController;
use App\Http\Controllers\CourseReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/certificates/{number}', [CertificateController::class, 'show'])->name('certificates.show');

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.attempt')->middleware('throttle:10,1');
    Route::post('/logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| Teacher
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Courses
    Route::resource('courses', CourseController::class)
        ->only(['index', 'create', 'store', 'show', 'update', 'destroy']);
    Route::patch('/courses/{course}/status', [CourseController::class, 'changeStatus'])
        ->name('courses.status.update');

    // Lessons
    Route::put('/courses/{course}/lessons/reorder', [LessonController::class, 'reorder'])
        ->name('courses.lessons.reorder');
    Route::resource('courses.lessons', LessonController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);

    // Students
    Route::resource('students', StudentController::class)->except(['show']);
    Route::get('/courses/{course}/students/create', [StudentController::class, 'createForCourse'])
        ->name('courses.students.create');
    Route::post('/courses/{course}/students', [StudentController::class, 'storeForCourse'])
        ->name('courses.students.store');

    // Enrollments
    Route::post('/courses/{course}/enrollments', [EnrollmentController::class, 'store'])
        ->name('courses.enrollments.store');
    Route::delete('/courses/{course}/enrollments/{student}', [EnrollmentController::class, 'destroy'])
        ->name('courses.enrollments.destroy');

    // Submissions
    Route::controller(SubmissionController::class)
        ->prefix('/submissions/{submission}')
        ->name('submissions.')
        ->group(function () {
            Route::get('/file', 'file')->name('file');
            Route::get('/preview', 'preview')->name('preview');
            Route::post('/review', 'review')->name('review');
            Route::post('/answers/{question}/grade', 'grade')->name('grade');
        });
});

/*
|--------------------------------------------------------------------------
| Student
|--------------------------------------------------------------------------
*/
Route::middleware('student.access')
    ->prefix('/students/{student}')
    ->whereNumber('student')
    ->name('learn.')
    ->group(function () {
        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
        Route::post('/courses/{course}/interest', [CourseInterestController::class, 'store'])->name('interest');

        Route::controller(LearningController::class)->group(function () {
            Route::get('/courses', 'courses')->name('courses');
            Route::get('/courses/{course}', 'course')->name('course');
            Route::get('/courses/{course}/lessons/{lesson}', 'lesson')->name('lesson');
            Route::post('/lessons/{lesson}/complete', 'complete')->name('complete');
            Route::post('/tasks/{task}/submissions', 'submit')->name('submit');
        });

        Route::post('/courses/{course}/certificate', [CertificateController::class, 'issue'])->name('certificate.issue');
        Route::post('/courses/{course}/review', [CourseReviewController::class, 'store'])->name('review.store');

        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifications', 'index')->name('notifications');
            Route::post('/notifications/read', 'readAll')->name('notifications.readAll');
            Route::post('/notifications/{notification}/read', 'read')->name('notifications.read');
        });
    });
