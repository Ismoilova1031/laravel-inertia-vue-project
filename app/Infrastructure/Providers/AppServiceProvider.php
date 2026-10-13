<?php

namespace App\Infrastructure\Providers;

use App\Domain\Student\PasswordHasherInterface;
use App\Application\Contracts\CourseLookupInterface;

use App\Infrastructure\Security\LaravelPasswordHasher;
use App\Infrastructure\Persistence\Eloquent\Queries\EloquentCourseLookup;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PasswordHasherInterface::class, LaravelPasswordHasher::class);
        $this->app->bind(CourseLookupInterface::class, EloquentCourseLookup::class);
    }
}