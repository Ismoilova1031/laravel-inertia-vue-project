<?php

use App\Providers\AppServiceProvider;
use App\Providers\RepositoryServiceProvider;
use App\Providers\UseCaseServiceProvider;
use App\Providers\ServiceBindingProvider;

return [
    AppServiceProvider::class,
    RepositoryServiceProvider::class,
    UseCaseServiceProvider::class,
    ServiceBindingProvider::class,
];
