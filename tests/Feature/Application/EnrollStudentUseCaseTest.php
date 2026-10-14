<?php
// tests/Feature/Application/EnrollStudentUseCaseTest.php
declare(strict_types=1);

namespace Tests\Feature\Application;

use App\Application\UseCase\Enrollment\DeactivateEnrollmentCommand;
use App\Application\UseCase\Enrollment\DeactivateEnrollmentUseCase;
use App\Application\UseCase\Enrollment\EnrollStudentCommand;
use App\Application\UseCase\Enrollment\EnrollStudentUseCase;
use App\Domain\Course\Exception\CourseNotPublishedException;
use App\Domain\Enrollment\Exception\AlreadyEnrolledException;
use App\Enums\CourseStatus;
use App\Infrastructure\Eloquent\StudentModel;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EnrollStudentUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private int $studentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->studentId = StudentModel::create([
            'name' => 'Ali', 'surname' => 'Valiyev',
            'email' => 'ali@example.com', 'username' => 'ali_v', 'password' => 'x',
        ])->id;
    }

    private function course(CourseStatus $status): Course
    {
        return Course::factory()->create(['status' => $status]);
    }

    private function enroll(int $courseId): void
    {
        $this->app->make(EnrollStudentUseCase::class)
            ->execute(new EnrollStudentCommand(courseId: $courseId, studentId: $this->studentId));
    }

    private function deactivate(int $courseId): void
    {
        $this->app->make(DeactivateEnrollmentUseCase::class)
            ->execute(new DeactivateEnrollmentCommand(courseId: $courseId, studentId: $this->studentId));
    }

    public function test_enrolls_student_in_published_course(): void
    {
        $course = $this->course(CourseStatus::PUBLISHED);

        $this->enroll($course->id);

        $this->assertDatabaseHas('course_student', [
            'course_id' => $course->id, 'student_id' => $this->studentId, 'status' => 1,
        ]);
    }

    public function test_rejects_draft_course(): void
    {
        $course = $this->course(CourseStatus::DRAFT);

        $this->expectException(CourseNotPublishedException::class);

        $this->enroll($course->id);
    }

    public function test_rejects_double_enrollment_while_active(): void
    {
        $course = $this->course(CourseStatus::PUBLISHED);
        $this->enroll($course->id);

        $this->expectException(AlreadyEnrolledException::class);

        $this->enroll($course->id);
    }

    public function test_deactivated_student_can_be_reenrolled_on_same_row(): void
    {
        $course = $this->course(CourseStatus::PUBLISHED);
        $this->enroll($course->id);

        $this->deactivate($course->id);
        $this->assertDatabaseHas('course_student', ['status' => 0]);

        $this->enroll($course->id);

        $this->assertDatabaseCount('course_student', 1);
        $this->assertDatabaseHas('course_student', ['status' => 1, 'deactivated_at' => null]);
    }
}