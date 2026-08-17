<?php

namespace App\Http\Controllers;

use App\Contracts\UseCases\GetCoursesUseCaseInterface;
use Inertia\Inertia;

class DashboardController extends Controller
{
    private GetCoursesUseCaseInterface $getCoursesUseCase;

    public function __construct(GetCoursesUseCaseInterface $getCoursesUseCase)
    {
        $this->getCoursesUseCase = $getCoursesUseCase;
    }

    public function index()
    {
        $courses = $this->getCoursesUseCase->execute();

        return Inertia::render('Dashboard', [
            'courses' => $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->description,
                    'category' => $course->category->label(),
                    'status' => $course->status->label(),
                ];
            }),
        ]);
    }
}