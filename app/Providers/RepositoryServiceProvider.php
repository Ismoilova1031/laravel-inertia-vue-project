<?php

namespace App\Providers;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\Repositories\LessonRepositoryInterface;
use App\Contracts\Repositories\StorageRepositoryInterface;
use App\Contracts\Repositories\TaskRepositoryInterface;

use App\Repositories\CourseRepository;
use App\Repositories\LessonRepository;
use App\Repositories\StorageRepository;
use App\Repositories\TaskRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
        $this->app->bind(StorageRepositoryInterface::class, StorageRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
    }

}