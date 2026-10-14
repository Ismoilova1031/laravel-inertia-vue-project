<?php

namespace Tests\Unit\Domain\Course;

use App\Domain\Course\Course;
use App\Domain\Course\CourseCategory;
use App\Domain\Course\CourseDescription;
use App\Domain\Course\CourseStatus;
use App\Domain\Course\CourseTitle;
use App\Domain\Course\Exception\CannotPublishWithoutLessonsException;
use App\Domain\Course\Exception\CannotDeleteNonDraftCourseException;
use PHPUnit\Framework\TestCase;

final class CourseTest extends TestCase
{
    private function course(): Course
    {
        return Course::register(new CourseTitle('PHP'), new CourseDescription('Asoslar'), CourseCategory::Programming);
    }

    public function test_new_course_is_draft(): void
    {
        $this->assertSame(CourseStatus::DRAFT, $this->course()->status());
    }

    public function test_cannot_publish_without_lessons(): void
    {
        $this->expectException(CannotPublishWithoutLessonsException::class);

        $this->course()->changeStatus(CourseStatus::PUBLISHED, 0);
    }

    public function test_publishes_with_lessons(): void
    {
        $c = $this->course();
        $c->changeStatus(CourseStatus::PUBLISHED, 3);

        $this->assertTrue($c->isPublished());
    }

    public function test_published_can_return_to_draft(): void
    {
        $c = $this->course();
        $c->changeStatus(CourseStatus::PUBLISHED, 1);

        $c->changeStatus(CourseStatus::DRAFT, 1);

        $this->assertSame(CourseStatus::DRAFT, $c->status());
    }

    public function test_archived_can_be_published_again_with_lessons(): void
    {
        $c = $this->course();
        $c->changeStatus(CourseStatus::PUBLISHED, 1);
        $c->changeStatus(CourseStatus::ARCHIVED, 1);

        $c->changeStatus(CourseStatus::PUBLISHED, 1);

        $this->assertTrue($c->isPublished());
    }

    public function test_archived_cannot_be_published_without_lessons(): void
    {
        $c = $this->course();
        $c->changeStatus(CourseStatus::ARCHIVED, 0);

        $this->expectException(CannotPublishWithoutLessonsException::class);

        $c->changeStatus(CourseStatus::PUBLISHED, 0);
    }

    public function test_same_status_is_noop(): void
    {
        $c = $this->course();
        $c->changeStatus(CourseStatus::DRAFT, 0);

        $this->assertSame(CourseStatus::DRAFT, $c->status());
    }

    public function test_draft_course_is_deletable(): void
    {
        $this->course()->assertDeletable();

        $this->assertTrue(true);
    }

    public function test_published_course_is_not_deletable(): void
    {
        $c = $this->course();
        $c->changeStatus(CourseStatus::PUBLISHED, 1);

        $this->expectException(CannotDeleteNonDraftCourseException::class);

        $c->assertDeletable();
    }

    public function test_archived_course_is_not_deletable(): void
    {
        $c = $this->course();
        $c->changeStatus(CourseStatus::ARCHIVED, 0);

        $this->expectException(CannotDeleteNonDraftCourseException::class);

        $c->assertDeletable();
    }
}
