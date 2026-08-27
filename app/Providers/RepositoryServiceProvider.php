<?php

namespace App\Providers;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\Repositories\LessonRepositoryInterface;
use App\Contracts\Repositories\StorageRepositoryInterface;
use App\Contracts\Repositories\TaskRepositoryInterface;
use App\Contracts\Repositories\QuestionRepositoryInterface;
use App\Contracts\Repositories\QuestionOptionRepositoryInterface;

use App\Repositories\CourseRepository;
use App\Repositories\LessonRepository;
use App\Repositories\StorageRepository;
use App\Repositories\TaskRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\QuestionOptionRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
        $this->app->bind(StorageRepositoryInterface::class, StorageRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(QuestionOptionRepositoryInterface::class, QuestionOptionRepository::class);
    }

}