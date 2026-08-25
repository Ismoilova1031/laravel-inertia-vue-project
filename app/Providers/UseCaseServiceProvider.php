<?php

namespace App\Providers;

use App\Contracts\UseCases\GetCoursesUseCaseInterface;
use App\Contracts\UseCases\CreateCourseUseCaseInterface;
use App\Contracts\UseCases\UpdateCourseUseCaseInterface;
use App\Contracts\UseCases\DeleteCourseUseCaseInterface;
use App\Contracts\UseCases\CreateLessonUseCaseInterface;
use App\Contracts\UseCases\ReorderLessonsUseCaseInterface;
use App\Contracts\UseCases\DeleteLessonUseCaseInterface;

use App\UseCases\GetCoursesUseCase;
use App\UseCases\CreateCourseUseCase;
use App\UseCases\UpdateCourseUseCase;
use App\UseCases\DeleteCourseUseCase;
use App\UseCases\CreateLessonUseCase;
use App\UseCases\ReorderLessonsUseCase;
use App\UseCases\DeleteLessonUseCase;   

use Illuminate\Support\ServiceProvider;

class UseCaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GetCoursesUseCaseInterface::class, GetCoursesUseCase::class);
        $this->app->bind(CreateCourseUseCaseInterface::class, CreateCourseUseCase::class);
        $this->app->bind(UpdateCourseUseCaseInterface::class, UpdateCourseUseCase::class);
        $this->app->bind(DeleteCourseUseCaseInterface::class, DeleteCourseUseCase::class);
        $this->app->bind(CreateLessonUseCaseInterface::class, CreateLessonUseCase::class);
        $this->app->bind(ReorderLessonsUseCaseInterface::class, ReorderLessonsUseCase::class);
        $this->app->bind(DeleteLessonUseCaseInterface::class, DeleteLessonUseCase::class);
    }
}