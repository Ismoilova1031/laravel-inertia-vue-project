<?php

namespace App\Infrastructure\Providers;

use App\Domain\Student\PasswordHasherInterface;

use App\Infrastructure\Security\LaravelPasswordHasher;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PasswordHasherInterface::class, LaravelPasswordHasher::class);
    }
}