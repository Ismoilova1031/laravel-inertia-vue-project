<?php

namespace App\Providers;

use App\Contracts\UseCases\CreateLessonUseCaseInterface;
use App\Contracts\UseCases\DeleteLessonUseCaseInterface;
use App\Contracts\UseCases\ReorderLessonsUseCaseInterface;
use App\Contracts\UseCases\UpdateLessonUseCaseInterface;
use App\UseCases\CreateLessonUseCase;
use App\UseCases\DeleteLessonUseCase;
use App\UseCases\ReorderLessonsUseCase;
use App\UseCases\UpdateLessonUseCase;
use Illuminate\Support\ServiceProvider;

class UseCaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CreateLessonUseCaseInterface::class, CreateLessonUseCase::class);
        $this->app->bind(ReorderLessonsUseCaseInterface::class, ReorderLessonsUseCase::class);
        $this->app->bind(DeleteLessonUseCaseInterface::class, DeleteLessonUseCase::class);
        $this->app->bind(UpdateLessonUseCaseInterface::class, UpdateLessonUseCase::class);
    }
}
