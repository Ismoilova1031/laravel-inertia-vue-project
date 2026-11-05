<?php

use App\Infrastructure\Provider\RepositoryServiceProvider as InfrastructureRepositoryServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    InfrastructureRepositoryServiceProvider::class,
];
