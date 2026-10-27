<?php

namespace App\Application\UseCase\Student;

use App\Application\UseCase\Enrollment\EnrollStudentCommand;
use App\Application\UseCase\Enrollment\EnrollStudentUseCase;
use App\Domain\Student\VO\StudentId;
use Illuminate\Support\Facades\DB;

final class RegisterAndEnrollStudentUseCase
{
    public function __construct(
        private readonly RegisterStudentUseCase $registerStudent,
        private readonly EnrollStudentUseCase $enrollStudent,
    ) {}

    public function execute(RegisterStudentCommand $command, int $courseId): StudentId
    {
        return DB::transaction(function () use ($command, $courseId) {
            $studentId = $this->registerStudent->execute($command);
            $this->enrollStudent->execute(new EnrollStudentCommand($courseId, $studentId->value()));

            return $studentId;
        });
    }
}
