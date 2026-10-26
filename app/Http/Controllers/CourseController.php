<?php

namespace App\Http\Controllers;

use App\Application\UseCase\Course\ChangeCourseStatusUseCase;
use App\Application\UseCase\Course\CreateCourseUseCase;
use App\Application\UseCase\Course\DeleteCourseUseCase;
use App\Application\UseCase\Course\GetCoursesUseCase;
use App\Application\UseCase\Course\GetCourseUseCase;
use App\Application\UseCase\Course\UpdateCourseUseCase;
use App\Application\UseCase\Student\GetAvailableStudentsUseCase;
use App\Application\UseCase\Assessment\GetCourseSubmissionsUseCase;
use App\Domain\Course\CourseCategory;
use App\Domain\Course\CourseStatus;
use App\Domain\Course\Exception\CannotDeleteNonDraftCourseException;
use App\Domain\Course\Exception\CannotPublishWithoutLessonsException;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Http\Requests\ChangeCourseStatusRequest;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseListResource;
use App\Http\Resources\CourseShowResource;
use App\Http\Resources\StudentListResource;
use App\Http\Resources\SubmissionRowResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function __construct(
        private readonly CreateCourseUseCase $createCourse,
        private readonly UpdateCourseUseCase $updateCourse,
        private readonly DeleteCourseUseCase $deleteCourse,
        private readonly GetCoursesUseCase $getCourses,
        private readonly GetCourseUseCase $getCourse,
        private readonly GetAvailableStudentsUseCase $getAvailableStudents,
        private readonly ChangeCourseStatusUseCase $changeCourseStatus,
        private readonly GetCourseSubmissionsUseCase $getSubmissions
    ) {}

    public function index(): Response
    {
        return Inertia::render('Courses/Index', [
            'courses' => CourseListResource::collection($this->getCourses->execute())->resolve(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Courses/Create', [
            'categories' => CourseCategory::labels(),
            'statuses' => CourseStatus::labels(),
        ]);
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        $id = $this->createCourse->execute($request->toCreateCommand());

        return to_route('courses.show', ['course' => $id->value()]);
    }

    public function show(int $course): Response
    {
        try {
            $detail = $this->getCourse->execute($course);
        } catch (CourseNotFoundException) {
            abort(404);
        }

        return Inertia::render('Courses/Show', [
            'course' => CourseShowResource::make($detail)->resolve(),
            'categories' => CourseCategory::labels(),
            'statuses' => CourseStatus::labels(),
            'availableStudents' => StudentListResource::collection(
                $this->getAvailableStudents->execute($course)
            )->resolve(),
            'submissions' => SubmissionRowResource::collection($this->getSubmissions->execute($course))->resolve(),
        ]);
    }

    public function update(CourseRequest $request, int $course): RedirectResponse
    {
        try {
            $this->updateCourse->execute($request->toUpdateCommand());
        } catch (CourseNotFoundException) {
            abort(404);
        } catch (CannotPublishWithoutLessonsException $e) {
            throw ValidationException::withMessages(['status' => $e->getMessage()]);
        }

        return to_route('courses.show', ['course' => $course]);
    }

    public function destroy(int $course): RedirectResponse
    {
        try {
            $this->deleteCourse->execute($course);
        } catch (CourseNotFoundException) {
            abort(404);
        } catch (CannotDeleteNonDraftCourseException $e) {
            return back()->withErrors(['course' => $e->getMessage()]);
        }

        return to_route('courses.index');
    }

    public function changeStatus(ChangeCourseStatusRequest $request, int $course): RedirectResponse
    {
        try {
            $this->changeCourseStatus->execute($request->toCommand());
        } catch (CourseNotFoundException) {
            abort(404);
        } catch (CannotPublishWithoutLessonsException $e) {
            throw ValidationException::withMessages(['course' => $e->getMessage()]);
        }

        return back();
    }
}
