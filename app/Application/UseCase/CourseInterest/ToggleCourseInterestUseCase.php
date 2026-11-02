<?php

namespace App\Application\UseCase\CourseInterest;

use App\Domain\Course\VO\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Course\Exception\CourseNotPublishedException;
use App\Domain\CourseInterest\CourseInterestRepositoryInterface;
use App\Domain\CourseInterest\InterestType;
use App\Domain\CourseInterest\NewCourseInterest;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\VO\StudentId;
use App\Domain\Student\StudentRepositoryInterface;

final class ToggleCourseInterestUseCase
{
    public function __construct(
        private readonly CourseInterestRepositoryInterface $interests,
        private readonly CourseRepositoryInterface $courses,
        private readonly StudentRepositoryInterface $students
    ) {}

    public function execute(int $studentId, int $courseId, InterestType $type, bool $enabled): void
    {
        $studentId = new StudentId($studentId);
        $courseId = new CourseId($courseId);

        $course = $this->courses->findById($courseId) ?? throw CourseNotFoundException::withId($courseId);
        $this->students->findById($studentId) ?? throw StudentNotFoundException::withId($studentId);

        if($enabled && !$course->isPublished()){
            throw CourseNotPublishedException::withId($courseId);
        }

        $interest = $this->interests->findByCourseAndStudent($courseId, $studentId);

        if($interest === null){
            if($enabled){
                $this->interests->create(NewCourseInterest::with($courseId, $studentId, $type));
            }

            return;
        }

        $enabled ? $interest->mark($type) : $interest->unmark($type);

        if($interest->isEmpty()){
            $this->interests->delete($interest);

            return;
        }

        $this->interests->save($interest);
    }
}
