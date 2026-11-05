<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LessonEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lesson = $this->resource->lesson;
        $task = $this->resource->task;

        return [
            'id' => $lesson->id()->value(),
            'title' => $lesson->title()->value(),
            'description' => $lesson->description()->value(),
            'lesson_type' => ['value' => $lesson->type()->value, 'label' => $lesson->type()->label()],
            'video_url' => $lesson->videoPath() ? Storage::url($lesson->videoPath()) : null,
            'lesson_content' => $lesson->content(),
            'sort_order' => $lesson->sortOrder()->value(),
            'task' => $this->when($task !== null, fn () => (new TaskResource($task))->resolve()),
        ];
    }
}
