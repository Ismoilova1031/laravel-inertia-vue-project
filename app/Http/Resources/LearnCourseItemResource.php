<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearnCourseItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $item = $this->resource;
        $course = $item->course;

        return [
            'id' => $course->id()->value(),
            'title' => $course->title()->value(),
            'description' => $course->description()->value(),
            'category' => $course->category()->label(),
            'status' => ['value' => $course->status()->value, 'label' => $course->status()->label()],
            'isActive' => $item->enrollment->isActive(),
            'progress' => [
                'total' => $item->progress->total,
                'completed' => $item->progress->completed,
                'percent' => $item->progress->percent(),
            ],
            'nextLessonId' => $item->progress->next()?->id()->value(),
        ];
    }
}