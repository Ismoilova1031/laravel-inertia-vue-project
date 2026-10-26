<?php

namespace App\Http\Resources;

use App\Domain\Lesson\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LearnLessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $value = $this->resource;
        $lesson = $value->lesson;
        $ref = fn (?Lesson $x) => $x ? ['id' => $x->id()->value(), 'title' => $x->title()->value()] : null;

        return [
            'id' => $lesson->id()->value(),
            'title' => $lesson->title()->value(),
            'description' => $lesson->description()->value(),
            'type' => ['value' => $lesson->type()->value, 'label' => $lesson->type()->label()],
            'content' => $lesson->content(),
            'videoUrl' => $lesson->videoPath() ? Storage::url($lesson->videoPath()) : null,
            'completed' => $value->completed,
            'course' => ['id' => $value->course->id()->value(), 'title' => $value->course->title()->value()],
            'previous' => $ref($value->previous),
            'next' => $ref($value->next),
            'task' => $value->task ? LearnTaskResource::make($value->task)->resolve() : null,
        ];
    }
}