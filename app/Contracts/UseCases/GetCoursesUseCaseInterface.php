<?php

namespace App\Contracts\UseCases;

use Illuminate\Support\Collection;

interface GetCoursesUseCaseInterface
{
    public function execute(): Collection;
}