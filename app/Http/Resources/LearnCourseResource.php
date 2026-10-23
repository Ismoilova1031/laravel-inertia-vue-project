<?php

namespace App\Http\Resources;

use App\Domain\Lesson\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearnCourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $v = $this->resource;
        $done = array_flip($v->completedLessonIds);

        return [
            'id' => $v->course->id()->value(),
            'title' => $v->course->title()->value(),
            'description' => $v->course->description()->value(),
            'category' => $v->course->category()->label(),
            'isActive' => $v->enrollment->isActive(),
            'progress' => [
                'total' => $v->progress->total,
                'completed' => $v->progress->completed,
                'percent' => $v->progress->percent(),
            ],
            'nextLessonId' => $v->progress->next()?->id()->value(),
            'lessons' => array_map(fn (Lesson $l) => [
                'id' => $l->id()->value(),
                'title' => $l->title()->value(),
                'description' => $l->description()->value(),
                'type' => ['value' => $l->type()->value, 'label' => $l->type()->label()],
                'completed' => isset($done[$l->id()->value()]),
            ], $v->lessons),
        ];
    }
}