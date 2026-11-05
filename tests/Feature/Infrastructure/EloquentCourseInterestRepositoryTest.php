<?php

namespace Tests\Feature\Infrastructure;

use App\Domain\Course\VO\CourseId;
use App\Domain\CourseInterest\CourseInterestRepositoryInterface;
use App\Domain\CourseInterest\InterestType;
use App\Domain\CourseInterest\NewCourseInterest;
use App\Domain\Student\VO\StudentId;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\StudentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EloquentCourseInterestRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CourseInterestRepositoryInterface $repository;

    private CourseId $courseId;

    private StudentId $studentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(CourseInterestRepositoryInterface::class);

        $course = CourseModel::factory()->create();
        $student = StudentModel::create([
            'name' => 'Ali',
            'surname' => 'Valiyev',
            'email' => 'ali@example.com',
            'username' => 'ali_v',
            'password' => 'x',
        ]);

        $this->courseId = new CourseId($course->id);
        $this->studentId = new StudentId($student->id);
    }

    public function test_create_persists_only_given_flag(): void
    {
        $saved = $this->repository->create(
            NewCourseInterest::with($this->courseId, $this->studentId, InterestType::LIKE)
        );

        $this->assertTrue($saved->has(InterestType::LIKE));
        $this->assertDatabaseHas('course_interests', ['is_liked' => 1, 'is_planned' => 0]);
    }

    public function test_save_updates_flags(): void
    {
        $saved = $this->repository->create(
            NewCourseInterest::with($this->courseId, $this->studentId, InterestType::PLANNED)
        );
        $saved->mark(InterestType::LIKE);

        $this->repository->save($saved);

        $this->assertDatabaseHas('course_interests', ['is_liked' => 1, 'is_planned' => 1]);
    }

    public function test_delete_removes_row(): void
    {
        $saved = $this->repository->create(
            NewCourseInterest::with($this->courseId, $this->studentId, InterestType::PLANNED)
        );

        $this->repository->delete($saved);

        $this->assertNull($this->repository->findByCourseAndStudent($this->courseId, $this->studentId));
    }
}
