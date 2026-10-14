<?php

namespace App\Infrastructure\Provider;

use App\Application\Contracts\CourseLookupInterface;
use App\Application\UseCase\Student\StudentQueryInterface;
use App\Domain\CourseInterest\CourseInterestRepositoryInterface;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Student\PasswordHasherInterface;
use App\Domain\Student\StudentRepositoryInterface;
use App\Infrastructure\Query\EloquentCourseLookup;
use App\Infrastructure\Query\EloquentStudentQuery;
use App\Infrastructure\Repository\EloquentCourseInterestRepository;
use App\Infrastructure\Repository\EloquentEnrollmentRepository;
use App\Infrastructure\Repository\EloquentStudentRepository;
use App\Infrastructure\Security\LaravelPasswordHasher;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repository
        $this->app->bind(StudentRepositoryInterface::class, EloquentStudentRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EloquentEnrollmentRepository::class);
        $this->app->bind(CourseInterestRepositoryInterface::class, EloquentCourseInterestRepository::class);

        // Query
        $this->app->bind(StudentQueryInterface::class, EloquentStudentQuery::class);

        // Port
        $this->app->bind(PasswordHasherInterface::class, LaravelPasswordHasher::class);
        $this->app->bind(CourseLookupInterface::class, EloquentCourseLookup::class);
    }
}
