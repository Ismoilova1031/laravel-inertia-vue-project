<?php

namespace App\Infrastructure\Provider;

use App\Application\UseCase\Assessment\TaskSubmissionLookupInterface;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\CourseInterest\CourseInterestRepositoryInterface;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Shared\FileStorageInterface;
use App\Domain\Student\PasswordHasherInterface;
use App\Domain\Student\StudentRepositoryInterface;
use App\Infrastructure\Query\EloquentTaskSubmissionLookup;
use App\Infrastructure\Repository\EloquentCourseInterestRepository;
use App\Infrastructure\Repository\EloquentCourseRepository;
use App\Infrastructure\Repository\EloquentEnrollmentRepository;
use App\Infrastructure\Repository\EloquentLessonRepository;
use App\Infrastructure\Repository\EloquentStudentRepository;
use App\Infrastructure\Repository\EloquentTaskRepository;
use App\Infrastructure\Service\LaravelFileStorage;
use App\Infrastructure\Service\LaravelPasswordHasher;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(StudentRepositoryInterface::class, EloquentStudentRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EloquentEnrollmentRepository::class);
        $this->app->bind(CourseInterestRepositoryInterface::class, EloquentCourseInterestRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, EloquentCourseRepository::class);

        $this->app->bind(PasswordHasherInterface::class, LaravelPasswordHasher::class);

        $this->app->bind(LessonRepositoryInterface::class, EloquentLessonRepository::class);
        $this->app->bind(FileStorageInterface::class, LaravelFileStorage::class);

        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
        $this->app->bind(TaskSubmissionLookupInterface::class, EloquentTaskSubmissionLookup::class);
    }
}
