<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lesson = $this->resource;

        return [
            'id' => $lesson->id()->value(),
            'title' => $lesson->title()->value(),
            'description' => $lesson->description()->value(),
            'lessonType' => ['value' => $lesson->type()->value, 'label' => $lesson->type()->label()],
            'lessonContent' => $lesson->content(),
            'videoUrl' => $lesson->videoPath(),
            'sortOrder' => $lesson->sortOrder()->value(),
        ];
    }
}
