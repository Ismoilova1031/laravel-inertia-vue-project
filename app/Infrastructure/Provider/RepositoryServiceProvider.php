<?php

namespace App\Infrastructure\Provider;

use App\Application\UseCase\Course\CourseLessonCounterInterface;
use App\Application\UseCase\Course\CourseQueryInterface;
use App\Application\UseCase\Student\StudentQueryInterface;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\CourseInterest\CourseInterestRepositoryInterface;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Student\PasswordHasherInterface;
use App\Domain\Student\StudentRepositoryInterface;
use App\Infrastructure\Query\EloquentCourseLessonCounter;
use App\Infrastructure\Query\EloquentCourseQuery;
use App\Infrastructure\Query\EloquentStudentQuery;
use App\Infrastructure\Repository\EloquentCourseInterestRepository;
use App\Infrastructure\Repository\EloquentCourseRepository;
use App\Infrastructure\Repository\EloquentEnrollmentRepository;
use App\Infrastructure\Repository\EloquentStudentRepository;
use App\Infrastructure\Security\LaravelPasswordHasher;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Application\UseCase\Lesson\LessonQueryInterface;
use App\Infrastructure\Repository\EloquentLessonRepository;
use App\Infrastructure\Query\EloquentLessonQuery;
use App\Application\Contracts\FileStorageInterface;
use App\Infrastructure\Storage\LaravelFileStorage;
use App\Application\UseCase\Lesson\LessonTaskGatewayInterface;
use App\Infrastructure\Legacy\LegacyLessonTaskGateway;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repository
        $this->app->bind(StudentRepositoryInterface::class, EloquentStudentRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EloquentEnrollmentRepository::class);
        $this->app->bind(CourseInterestRepositoryInterface::class, EloquentCourseInterestRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, EloquentCourseRepository::class);

        // Query
        $this->app->bind(StudentQueryInterface::class, EloquentStudentQuery::class);
        $this->app->bind(CourseQueryInterface::class, EloquentCourseQuery::class);

        // Port
        $this->app->bind(PasswordHasherInterface::class, LaravelPasswordHasher::class);
        $this->app->bind(CourseLessonCounterInterface::class, EloquentCourseLessonCounter::class);

        $this->app->bind(LessonRepositoryInterface::class, EloquentLessonRepository::class);
        $this->app->bind(LessonQueryInterface::class, EloquentLessonQuery::class);
        $this->app->bind(FileStorageInterface::class, LaravelFileStorage::class);
        $this->app->bind(LessonTaskGatewayInterface::class, LegacyLessonTaskGateway::class);
    }
}
