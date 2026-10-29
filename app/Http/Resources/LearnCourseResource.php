<?php

namespace App\Http\Resources;

use App\Domain\Lesson\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearnCourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $value = $this->resource;
        $done = array_flip($value->completedLessonIds);

        return [
            'id' => $value->course->id()->value(),
            'title' => $value->course->title()->value(),
            'description' => $value->course->description()->value(),
            'category' => $value->course->category()->label(),
            'isActive' => $value->enrollment->isActive(),
            'progress' => [
                'total' => $value->progress->total,
                'completed' => $value->progress->completed,
                'percent' => $value->progress->percent(),
            ],
            'nextLessonId' => $value->progress->next()?->id()->value(),
            'lessons' => array_map(fn(Lesson $lesson) => [
                'id' => $lesson->id()->value(),
                'title' => $lesson->title()->value(),
                'description' => $lesson->description()->value(),
                'type' => ['value' => $lesson->type()->value, 'label' => $lesson->type()->label()],
                'completed' => isset($done[$lesson->id()->value()]),
            ], $value->lessons),
            'isComplete' => $value->progress->isComplete(),
            'certificate' => $value->certificate ? ['number' => $value->certificate->number()->value()] : null,
            'rating' => ['average' => $value->rating->average, 'count' => $value->rating->count],
            'myReview' => $value->review
                ? ['rating' => $value->review->rating()->value(), 'comment' => $value->review->comment()->value()]
                : null,
        ];
    }
}
