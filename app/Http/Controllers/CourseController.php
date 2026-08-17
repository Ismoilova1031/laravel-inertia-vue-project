<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Contracts\UseCases\CreateCourseUseCaseInterface;
use App\Dtos\CourseDto;
use App\Enums\CourseCategory;
use App\Enums\CourseStatus;

class CourseController extends Controller
{
    private CreateCourseUseCaseInterface $createCourseUseCase;

    public function __construct(CreateCourseUseCaseInterface $createCourseUseCase){
        $this->createCourseUseCase = $createCourseUseCase;
    }

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

    public function store(StoreCourseRequest $request){
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
}
