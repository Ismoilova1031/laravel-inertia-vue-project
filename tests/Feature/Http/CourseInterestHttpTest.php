<?php
// tests/Feature/Http/CourseInterestHttpTest.php
declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Domain\Course\CourseStatus;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\StudentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CourseInterestHttpTest extends TestCase
{
    use RefreshDatabase;

    private function student(string $username): StudentModel
    {
        return StudentModel::create([
            'name' => 'Ali', 'surname' => 'V', 'email' => "$username@example.com",
            'username' => $username, 'password' => 'secret123',
        ]);
    }

    public function test_student_can_like_a_course(): void
    {
        $me = $this->student('ali');
        $course = CourseModel::factory()->create(['status' => CourseStatus::PUBLISHED]);

        $this->actingAs($me, 'student')
            ->post("/students/{$me->id}/courses/{$course->id}/interest", ['type' => 'like', 'enabled' => true])
            ->assertRedirect();

        $this->assertDatabaseHas('course_interests', ['student_id' => $me->id, 'course_id' => $course->id, 'is_liked' => 1]);
    }

    public function test_student_cannot_change_another_students_interests(): void
    {
        $me = $this->student('ali');
        $other = $this->student('vali');
        $course = CourseModel::factory()->create(['status' => CourseStatus::PUBLISHED]);

        $this->actingAs($me, 'student')
            ->post("/students/{$other->id}/courses/{$course->id}/interest", ['type' => 'like', 'enabled' => true])
            ->assertForbidden();

        $this->assertDatabaseCount('course_interests', 0);
    }

    public function test_invalid_type_is_rejected(): void
    {
        $me = $this->student('ali');
        $course = CourseModel::factory()->create(['status' => CourseStatus::PUBLISHED]);

        $this->actingAs($me, 'student')
            ->post("/students/{$me->id}/courses/{$course->id}/interest", ['type' => 'nope', 'enabled' => true])
            ->assertSessionHasErrors('type');
    }
}