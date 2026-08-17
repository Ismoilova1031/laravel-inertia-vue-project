<?php

namespace App\Http\Controllers;

use App\Contracts\UseCases\GetCoursesUseCaseInterface;
use Inertia\Inertia;
use App\Dtos\CourseResponseDto;

class DashboardController extends Controller
{
    public function __construct(
        private GetCoursesUseCaseInterface $getCoursesUseCase,
    ) {}

    public function index()
    {
        $courses = $this->getCoursesUseCase->execute();

        return Inertia::render('Dashboard', [
            'courses' => $courses
            ->map(fn ($course) => CourseResponseDto::fromModel($course))
            ->values(),
        ]);
    }
}