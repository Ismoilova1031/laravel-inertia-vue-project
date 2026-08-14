<?php

namespace App\Providers;

use App\Contracts\UseCases\GetCoursesUseCaseInterface;
use App\Contracts\UseCases\CreateCourseUseCaseInterface;

use App\UseCases\GetCoursesUseCase;
use App\UseCases\CreateCourseUseCase;

use Illuminate\Support\ServiceProvider;

class UseCaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GetCoursesUseCaseInterface::class, GetCoursesUseCase::class);
        $this->app->bind(CreateCourseUseCaseInterface::class, CreateCourseUseCase::class);
    }
}