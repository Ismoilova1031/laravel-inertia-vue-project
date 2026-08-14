<?php

namespace App\Providers;

use App\Contracts\Repositories\CourseRepositoryInterface;

use App\Repositories\CourseRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
    }

}