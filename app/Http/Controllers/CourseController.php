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

class CourseController extends Controller
{
    public function __construct(
        private CreateCourseUseCaseInterface $createCourseUseCase,
        private UpdateCourseUseCaseInterface $updateCourseUseCase,
        private DeleteCourseUseCaseInterface $deleteCourseUseCase,
    ) {}

    public function show(Course $course)
    {
        return Inertia::render('Courses/Show', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'category' =>  $course->category->label(),
                'status' => $course->status->label(),
            ],
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

        $dto = new CourseDto(
            title: $validated['title'],
            description: $validated['description'],
            category: $validated['category'],
            status: $validated['status']
        );

        $course = $this->createCourseUseCase->execute($dto);

        return redirect()->route('courses.show', ['course' => $course]);
    }

public function edit(Course $course)
{
    return Inertia::render('Courses/Edit', [
        'course' => [
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'category' => [
                'value' => $course->category->value,
                'label' => $course->category->label(),
            ],
            'status' => [
                'value' => $course->status->value,
                'label' => $course->status->label(),
            ],
        ],
        'categories' => CourseCategory::labels(),
        'statuses' => CourseStatus::labels(),
    ]);
}

    public function update(CourseRequest $request, Course $course)
    {
        $validated = $request->validated();

       $dto = new CourseDto(
            title: $validated['title'],
            description: $validated['description'],
            category: $validated['category'],
            status: $validated['status']
        );

        $course = $this->updateCourseUseCase->execute($course, $dto);

        return redirect()->route('courses.show', ['course' => $course]);
    }

    public function destroy(Course $course)
    {
        $this->deleteCourseUseCase->execute($course);

        return redirect()->route('dashboard');
    }
}
