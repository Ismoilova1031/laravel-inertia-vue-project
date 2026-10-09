<?php

namespace App\Infrastructure\Providers;

use App\Domain\Student\StudentRepositoryInterface;

use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentStudentRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(StudentRepositoryInterface::class, EloquentStudentRepository::class);
    }
}