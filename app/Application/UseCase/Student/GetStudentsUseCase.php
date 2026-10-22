<?php

namespace App\Application\UseCase\Student;

use App\Domain\Student\StudentRepositoryInterface;
use Illuminate\Support\Collection;

final class GetStudentsUseCase
{
    public function __construct(private readonly StudentRepositoryInterface $students) {}

    public function execute(): Collection
    {
        return $this->students->all();
    }
}
