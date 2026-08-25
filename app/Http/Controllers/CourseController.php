<?php

namespace App\Http\Controllers;

use App\Contracts\UseCases\CreateCourseUseCaseInterface;
use App\Contracts\UseCases\DeleteCourseUseCaseInterface;
use App\Contracts\UseCases\UpdateCourseUseCaseInterface;
use App\Contracts\UseCases\GetCoursesUseCaseInterface;
use App\Dtos\CourseDto;
use App\Enums\CourseCategory;
use App\Enums\CourseStatus;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseListResource;
use App\Http\Resources\CourseShowResource;
use App\Models\Course;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function __construct(
        private CreateCourseUseCaseInterface $createCourseUseCase,
        private UpdateCourseUseCaseInterface $updateCourseUseCase,
        private DeleteCourseUseCaseInterface $deleteCourseUseCase,
        private GetCoursesUseCaseInterface $getCoursesUseCase
    ) {}

    public function show(Course $course)
    {
        return Inertia::render('Courses/Show', [
            'course' => CourseShowResource::make($course)->resolve(),
            'categories' => CourseCategory::labels(),
            'statuses' => CourseStatus::labels(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Courses/Create', [
            'categories' => CourseCategory::labels(),
            'statuses' => CourseStatus::labels(),
        ]);
    }

    public function store(CourseRequest $request)
    {
        $validated = $request->validated();

        $dto = CourseDto::fromArray($validated);

        $course = $this->createCourseUseCase->execute($dto);

        return redirect()->route('courses.show', ['course' => $course]);
    }

    public function update(CourseRequest $request, Course $course)
    {
        $validated = $request->validated();

        $dto = CourseDto::fromArray($validated);

        $course = $this->updateCourseUseCase->execute($course, $dto);

        return redirect()->route('courses.show', ['course' => $course]);
    }

    public function destroy(Course $course)
    {
        $this->deleteCourseUseCase->execute($course);

        return redirect()->route('courses.index');
    }

    public function index()
    {
        $courses = $this->getCoursesUseCase->execute();

        return Inertia::render('Courses/Index', [
            'courses' => CourseListResource::collection($courses)->resolve(),
        ]);
    }
}
