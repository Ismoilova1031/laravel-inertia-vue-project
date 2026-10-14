<?php

use App\Infrastructure\Provider\RepositoryServiceProvider as InfrastructureRepositoryServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\RepositoryServiceProvider;
use App\Providers\ServiceBindingProvider;
use App\Providers\UseCaseServiceProvider;

return [
    AppServiceProvider::class,
    RepositoryServiceProvider::class,
    UseCaseServiceProvider::class,
    ServiceBindingProvider::class,
    InfrastructureRepositoryServiceProvider::class,
];
