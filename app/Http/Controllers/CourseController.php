<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Contracts\UseCases\CreateCourseUseCaseInterface;
use App\Dtos\CourseDto;

class CourseController extends Controller
{
    private CreateCourseUseCaseInterface $createCourseUseCase;

    public function __construct(CreateCourseUseCaseInterface $createCourseUseCase){
        $this->createCourseUseCase = $createCourseUseCase;
    }

    public function show(Course $course)
    {
        return Inertia::render('Courses/Show', [
            'course' => $course->only(['id', 'title', 'description']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Courses/Create');
    }

    public function store(StoreCourseRequest $request){
        $validated = $request->validated();

        $dto = new CourseDto(
            title: $validated['title'],
            description: $validated['description']
        );

        $course = $this->createCourseUseCase->execute($dto);

        return redirect()->route('courses.show', ['course' => $course]);
    }
}