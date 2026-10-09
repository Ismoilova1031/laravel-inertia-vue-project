<?php

use App\Providers\AppServiceProvider;
use App\Providers\RepositoryServiceProvider;
use App\Providers\UseCaseServiceProvider;
use App\Providers\ServiceBindingProvider;
use App\Infrastructure\Providers\RepositoryServiceProvider as InfrastructureRepositoryServiceProvider;

return [
    AppServiceProvider::class,
    RepositoryServiceProvider::class,
    UseCaseServiceProvider::class,
    ServiceBindingProvider::class,
    InfrastructureRepositoryServiceProvider::class,
];
