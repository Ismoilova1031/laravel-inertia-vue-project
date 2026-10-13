<?php

namespace App\Infrastructure\Providers;

use App\Domain\Student\StudentRepositoryInterface;
use App\Application\Student\GetStudents\StudentQueryInterface;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\CourseInterest\CourseInterestRepositoryInterface;

use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentStudentRepository;
use App\Infrastructure\Persistence\Eloquent\Queries\EloquentStudentQuery;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentEnrollmentRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentCourseInterestRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(StudentRepositoryInterface::class, EloquentStudentRepository::class);
        $this->app->bind(StudentQueryInterface::class, EloquentStudentQuery::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EloquentEnrollmentRepository::class);
        $this->app->bind(CourseInterestRepositoryInterface::class, EloquentCourseInterestRepository::class);
    }
}
