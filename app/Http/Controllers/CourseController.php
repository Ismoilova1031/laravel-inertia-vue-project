<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Course;
use App\Http\Requests\CourseRequest;
use App\Contracts\UseCases\CreateCourseUseCaseInterface;
use App\Contracts\UseCases\UpdateCourseUseCaseInterface;
use App\Contracts\UseCases\DeleteCourseUseCaseInterface;
use App\Dtos\CourseDto;
use App\Enums\CourseCategory;
use App\Enums\CourseStatus;
use App\Dtos\CourseDetailResponseDto;

class CourseController extends Controller
{
    public function __construct(
        private CreateCourseUseCaseInterface $createCourseUseCase,
        private UpdateCourseUseCaseInterface $updateCourseUseCase,
        private DeleteCourseUseCaseInterface $deleteCourseUseCase,
    ) {}

    public function show(Course $course)
    {
        $course->load([
            'lessons',
            'students',
        ]);
        return Inertia::render('Courses/Show', [
            'course' => CourseDetailResponseDto::fromModel($course),
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

    public function store(CourseRequest $request){
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

        return redirect()->route('dashboard');
    }
}
