<?php

namespace App\Providers;

use App\Contracts\Repositories\QuestionOptionRepositoryInterface;
use App\Contracts\Repositories\QuestionRepositoryInterface;
use App\Contracts\Repositories\TaskRepositoryInterface;
use App\Repositories\QuestionOptionRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\TaskRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(QuestionOptionRepositoryInterface::class, QuestionOptionRepository::class);
    }
}
