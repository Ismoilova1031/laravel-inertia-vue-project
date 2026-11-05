<?php

// tests/Feature/Application/CourseInterestUseCasesTest.php
declare(strict_types=1);

namespace Tests\Feature\Application;

use App\Application\UseCase\CourseInterest\GetCatalogUseCase;
use App\Application\UseCase\CourseInterest\ToggleCourseInterestUseCase;
use App\Application\UseCase\Enrollment\EnrollStudentCommand;
use App\Application\UseCase\Enrollment\EnrollStudentUseCase;
use App\Domain\Course\CourseStatus;
use App\Domain\Course\Exception\CourseNotPublishedException;
use App\Domain\CourseInterest\InterestType;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\StudentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class CourseInterestUseCasesTest extends TestCase
{
    use RefreshDatabase;

    private int $studentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->studentId = StudentModel::create([
            'name' => 'Ali', 'surname' => 'V', 'email' => 'a@example.com', 'username' => 'ali_v', 'password' => 'x',
        ])->id;
    }

    private function course(CourseStatus $status = CourseStatus::PUBLISHED): int
    {
        return CourseModel::factory()->create(['status' => $status])->id;
    }

    private function toggle(int $courseId, InterestType $type, bool $enabled): void
    {
        $this->app->make(ToggleCourseInterestUseCase::class)->execute($this->studentId, $courseId, $type, $enabled);
    }

    public function test_like_and_planned_share_one_row_and_are_independent(): void
    {
        $course = $this->course();

        $this->toggle($course, InterestType::LIKE, true);
        $this->toggle($course, InterestType::PLANNED, true);

        $this->assertDatabaseCount('course_interests', 1);
        $this->assertDatabaseHas('course_interests', ['is_liked' => 1, 'is_planned' => 1]);

        $this->toggle($course, InterestType::LIKE, false);

        $this->assertDatabaseHas('course_interests', ['is_liked' => 0, 'is_planned' => 1]);
    }

    public function test_row_is_deleted_when_both_flags_are_off(): void
    {
        $course = $this->course();
        $this->toggle($course, InterestType::LIKE, true);

        $this->toggle($course, InterestType::LIKE, false);

        $this->assertDatabaseCount('course_interests', 0);
    }

    public function test_disabling_without_row_is_a_noop_and_enabling_is_idempotent(): void
    {
        $course = $this->course();

        $this->toggle($course, InterestType::LIKE, false);
        $this->assertDatabaseCount('course_interests', 0);

        $this->toggle($course, InterestType::LIKE, true);
        $this->toggle($course, InterestType::LIKE, true);
        $this->assertDatabaseCount('course_interests', 1);
    }

    public function test_cannot_mark_unpublished_course_but_can_unmark_it(): void
    {
        $course = $this->course();
        $this->toggle($course, InterestType::LIKE, true);

        CourseModel::whereKey($course)->update(['status' => CourseStatus::ARCHIVED]);
        $this->toggle($course, InterestType::LIKE, false);
        $this->assertDatabaseCount('course_interests', 0);

        $this->expectException(CourseNotPublishedException::class);
        $this->toggle($course, InterestType::LIKE, true);
    }

    public function test_catalog_lists_only_published_with_flags_enrollment_and_rating(): void
    {
        $published = $this->course();
        $this->course(CourseStatus::DRAFT);
        $this->course(CourseStatus::ARCHIVED);

        $this->toggle($published, InterestType::PLANNED, true);
        $this->app->make(EnrollStudentUseCase::class)->execute(new EnrollStudentCommand($published, $this->studentId));

        DB::table('course_reviews')->insert([
            ['course_id' => $published, 'student_id' => $this->studentId, 'rating' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $items = $this->app->make(GetCatalogUseCase::class)->execute($this->studentId);

        $this->assertCount(1, $items);
        $this->assertFalse($items[0]->liked);
        $this->assertTrue($items[0]->planned);
        $this->assertTrue($items[0]->enrolled);
        $this->assertSame(4.0, $items[0]->rating->average);
        $this->assertSame(1, $items[0]->rating->count);
    }
}
