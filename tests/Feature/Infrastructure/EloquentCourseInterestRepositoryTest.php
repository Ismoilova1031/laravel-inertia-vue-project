<?php
// tests/Feature/Infrastructure/EloquentCourseInterestRepositoryTest.php
declare(strict_types=1);

namespace Tests\Feature\Infrastructure;

use App\Domain\Course\CourseId;
use App\Domain\CourseInterest\CourseInterest;
use App\Domain\CourseInterest\CourseInterestRepositoryInterface;
use App\Domain\CourseInterest\InterestType;
use App\Domain\Student\StudentId;
use App\Infrastructure\Persistence\Eloquent\Models\StudentModel;
use App\Models\Course;
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

        $course = Course::factory()->create();
        $student = StudentModel::create([
            'name' => 'Ali', 'surname' => 'Valiyev',
            'email' => 'ali@example.com', 'username' => 'ali_v', 'password' => 'x',
        ]);

        $this->courseId = new CourseId($course->id);
        $this->studentId = new StudentId($student->id);
    }

    public function test_save_persists_flags(): void
    {
        $interest = CourseInterest::create($this->courseId, $this->studentId);
        $interest->mark(InterestType::LIKE);

        $saved = $this->repository->save($interest);

        $this->assertNotNull($saved->id());
        $this->assertDatabaseHas('course_interests', ['is_liked' => 1, 'is_planned' => 0]);
    }

    public function test_delete_removes_row(): void
    {
        $interest = CourseInterest::create($this->courseId, $this->studentId);
        $interest->mark(InterestType::PLANNED);
        $saved = $this->repository->save($interest);

        $this->repository->delete($saved);

        $this->assertNull($this->repository->findByCourseAndStudent($this->courseId, $this->studentId));
    }
}