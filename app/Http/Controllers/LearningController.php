<?php

namespace App\Http\Controllers;

use App\Application\UseCase\Progress\GetStudentCourseUseCase;
use App\Application\UseCase\Progress\GetStudentCoursesUseCase;
use App\Application\UseCase\Progress\GetStudentLessonUseCase;
use App\Application\UseCase\Progress\MarkLessonCompletedUseCase;
use App\Application\UseCase\Student\GetStudentUseCase;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Enrollment\Exception\EnrollmentNotFoundException;
use App\Domain\Enrollment\Exception\StudentNotActiveInCourseException;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Progress\Exception\LessonNotCompletableManuallyException;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Http\Resources\LearnCourseItemResource;
use App\Http\Resources\LearnCourseResource;
use App\Http\Resources\LearnLessonResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

final class LearningController extends Controller
{
    public function __construct(
        private readonly GetStudentUseCase $getStudent,
        private readonly GetStudentCoursesUseCase $getCourses,
        private readonly GetStudentCourseUseCase $getCourse,
        private readonly GetStudentLessonUseCase $getLesson,
        private readonly MarkLessonCompletedUseCase $markCompleted,
    ) {}

    public function courses(int $student): Response
    {
        return Inertia::render('Learn/Courses', [
            'student' => $this->studentProps($student),
            'courses' => LearnCourseItemResource::collection($this->getCourses->execute($student))->resolve(),
        ]);
    }

    public function course(int $student, int $course): Response
    {
        $props = $this->studentProps($student);

        try {
            $view = $this->getCourse->execute($student, $course);
        } catch (CourseNotFoundException | EnrollmentNotFoundException) {
            abort(404);
        }

        return Inertia::render('Learn/Course', [
            'student' => $props,
            'course' => LearnCourseResource::make($view)->resolve(),
        ]);
    }

    public function lesson(int $student, int $course, int $lesson): Response
    {
        $props = $this->studentProps($student);

        try {
            $view = $this->getLesson->execute($student, $lesson);
        } catch (LessonNotFoundException | CourseNotFoundException) {
            abort(404);
        } catch (StudentNotActiveInCourseException) {
            abort(403, 'Bu kursga kirish huquqingiz yo\'q.');
        }

        return Inertia::render('Learn/Lesson', [
            'student' => $props,
            'lesson' => LearnLessonResource::make($view)->resolve(),
        ]);
    }

    public function complete(int $student, int $lesson): RedirectResponse
    {
        try {
            $this->markCompleted->execute($lesson, $student);
        } catch (LessonNotFoundException) {
            abort(404);
        } catch (StudentNotActiveInCourseException) {
            abort(403);
        } catch (LessonNotCompletableManuallyException $e) {
            throw ValidationException::withMessages(['lesson' => $e->getMessage()]);
        }

        return back();
    }

    /** @return array{id: int, fullName: string} */
    private function studentProps(int $id): array
    {
        try {
            $s = $this->getStudent->execute($id);
        } catch (StudentNotFoundException) {
            abort(404);
        }

        return ['id' => $s->id()->value(), 'fullName' => $s->name()->fullName()];
    }
}