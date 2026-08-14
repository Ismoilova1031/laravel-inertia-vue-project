<?php

namespace App\Providers;

use App\Contracts\UseCases\GetCoursesUseCaseInterface;

use App\UseCases\GetCoursesUseCase;

use Illuminate\Support\ServiceProvider;

class UseCaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GetCoursesUseCaseInterface::class, GetCoursesUseCase::class);
    }
}