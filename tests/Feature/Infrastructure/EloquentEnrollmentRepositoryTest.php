<?php

// tests/Feature/Infrastructure/EloquentEnrollmentRepositoryTest.php
declare(strict_types=1);

namespace Tests\Feature\Infrastructure;

use App\Domain\Course\CourseId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\Student\StudentId;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\StudentModel;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentEnrollmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EnrollmentRepositoryInterface $repository;

    private CourseId $courseId;

    private StudentId $studentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(EnrollmentRepositoryInterface::class);

        $course = CourseModel::factory()->create();
        $student = StudentModel::create([
            'name' => 'Ali', 'surname' => 'Valiyev',
            'email' => 'ali@example.com', 'username' => 'ali_v', 'password' => 'x',
        ]);

        $this->courseId = new CourseId($course->id);
        $this->studentId = new StudentId($student->id);
    }

    public function test_save_assigns_id_and_persists_status_as_int(): void
    {
        $saved = $this->repository->save(
            Enrollment::enroll($this->courseId, $this->studentId, new DateTimeImmutable('2026-01-01'))
        );

        $this->assertNotNull($saved->id());
        $this->assertDatabaseHas('course_student', ['status' => 1]);
    }

    public function test_deactivate_then_reenroll_updates_same_row(): void
    {
        $saved = $this->repository->save(
            Enrollment::enroll($this->courseId, $this->studentId, new DateTimeImmutable('2026-01-01'))
        );

        $saved->deactivate(new DateTimeImmutable('2026-02-01'));
        $this->repository->save($saved);
        $this->assertDatabaseHas('course_student', ['status' => 0]);

        $found = $this->repository->findByCourseAndStudent($this->courseId, $this->studentId);
        $this->assertSame(EnrollmentStatus::PASSIVE, $found->status());

        $found->reenroll(new DateTimeImmutable('2026-03-01'));
        $this->repository->save($found);

        $this->assertDatabaseCount('course_student', 1);
        $this->assertDatabaseHas('course_student', ['status' => 1, 'deactivated_at' => null]);
    }

    public function test_find_returns_null_when_not_enrolled(): void
    {
        $this->assertNull($this->repository->findByCourseAndStudent($this->courseId, $this->studentId));
    }
}
