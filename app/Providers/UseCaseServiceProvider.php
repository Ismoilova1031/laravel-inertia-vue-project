<?php

namespace App\Providers;

use App\Contracts\UseCases\GetCoursesUseCaseInterface;
use App\Contracts\UseCases\CreateCourseUseCaseInterface;
use App\Contracts\UseCases\UpdateCourseUseCaseInterface;
use App\Contracts\UseCases\DeleteCourseUseCaseInterface;

use App\UseCases\GetCoursesUseCase;
use App\UseCases\CreateCourseUseCase;
use App\UseCases\UpdateCourseUseCase;
use App\UseCases\DeleteCourseUseCase;

use Illuminate\Support\ServiceProvider;

class UseCaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GetCoursesUseCaseInterface::class, GetCoursesUseCase::class);
        $this->app->bind(CreateCourseUseCaseInterface::class, CreateCourseUseCase::class);
        $this->app->bind(UpdateCourseUseCaseInterface::class, UpdateCourseUseCase::class);
        $this->app->bind(DeleteCourseUseCaseInterface::class, DeleteCourseUseCase::class);
    }
}