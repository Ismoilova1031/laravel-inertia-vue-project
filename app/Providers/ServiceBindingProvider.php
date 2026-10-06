<?php

namespace App\Providers;

use App\Contracts\Services\SaveVideoServiceInterface;
use App\Contracts\Services\CreateLessonServiceInterface;
use App\Contracts\Services\UpdateLessonServiceInterface;

use App\Services\SaveVideoService;
use App\Services\CreateLessonService;
use App\Services\UpdateLessonService;

use Illuminate\Support\ServiceProvider; 

class ServiceBindingProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SaveVideoServiceInterface::class, SaveVideoService::class);
        $this->app->bind(CreateLessonServiceInterface::class, CreateLessonService::class);
        $this->app->bind(UpdateLessonServiceInterface::class, UpdateLessonService::class);
    }
}