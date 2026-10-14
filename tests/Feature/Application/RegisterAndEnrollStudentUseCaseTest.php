<?php

namespace Tests\Feature\Application;

use App\Application\UseCase\Student\RegisterAndEnrollStudentUseCase;
use App\Application\UseCase\Student\RegisterStudentCommand;
use App\Domain\Course\Exception\CourseNotPublishedException;
use App\Enums\CourseStatus;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RegisterAndEnrollStudentUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private function command(): RegisterStudentCommand
    {
        return new RegisterStudentCommand(
            name: 'Ali',
            surname: 'Valiyev',
            email: 'ali@example.com',
            username: 'ali_v',
            password: 'secret123',
        );
    }

    public function test_creates_student_and_enrollment_for_published_course(): void
    {
        $course = Course::factory()->create(['status' => CourseStatus::PUBLISHED]);

        $id = $this->app->make(RegisterAndEnrollStudentUseCase::class)
            ->execute($this->command(), $course->id);

        $this->assertDatabaseHas('students', ['id' => $id->value(), 'username' => 'ali_v']);
        $this->assertDatabaseHas('course_student', [
            'course_id' => $course->id, 'student_id' => $id->value(), 'status' => 1,
        ]);
    }

    public function test_student_is_not_created_when_course_is_draft(): void
    {
        $course = Course::factory()->create(['status' => CourseStatus::DRAFT]);

        try {
            $this->app->make(RegisterAndEnrollStudentUseCase::class)
                ->execute($this->command(), $course->id);
            $this->fail('CourseNotPublishedException kutilgan edi.');
        } catch (CourseNotPublishedException) {
        }

        $this->assertDatabaseCount('students', 0);
        $this->assertDatabaseCount('course_student', 0);
    }
}