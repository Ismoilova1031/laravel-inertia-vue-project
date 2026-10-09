<?php

namespace App\Infrastructure\Providers;

use App\Domain\Student\StudentRepositoryInterface;
use App\Application\Student\GetStudents\StudentQueryInterface;

use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentStudentRepository;
use App\Infrastructure\Persistence\Eloquent\Queries\EloquentStudentQuery;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(StudentRepositoryInterface::class, EloquentStudentRepository::class);
        $this->app->bind(StudentQueryInterface::class,EloquentStudentQuery::class);
    }
}
