<?php

namespace Tests\Unit\Domain\Course;

use App\Domain\Course\Course;
use App\Domain\Course\CourseCategory;
use App\Domain\Course\CourseDescription;
use App\Domain\Course\CourseStatus;
use App\Domain\Course\CourseTitle;
use App\Domain\Course\Exception\CannotPublishWithoutLessonsException;
use App\Domain\Course\Exception\InvalidCourseStatusTransitionException;
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

    public function test_published_cannot_return_to_draft(): void
    {
        $c = $this->course();
        $c->changeStatus(CourseStatus::PUBLISHED, 1);

        $this->expectException(InvalidCourseStatusTransitionException::class);

        $c->changeStatus(CourseStatus::DRAFT, 1);
    }

    public function test_published_can_be_archived_but_not_republished(): void
    {
        $c = $this->course();
        $c->changeStatus(CourseStatus::PUBLISHED, 1);
        $c->changeStatus(CourseStatus::ARCHIVED, 1);

        $this->assertSame(CourseStatus::ARCHIVED, $c->status());

        $this->expectException(InvalidCourseStatusTransitionException::class);
        $c->changeStatus(CourseStatus::PUBLISHED, 1);
    }

    public function test_same_status_is_noop(): void
    {
        $c = $this->course();
        $c->changeStatus(CourseStatus::DRAFT, 0);

        $this->assertSame(CourseStatus::DRAFT, $c->status());
    }
}
