<?php

namespace App\Http\Resources;

use App\Domain\Lesson\LessonType;
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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'lesson_type' => ['value' => $this->type->value, 'label' => $this->type->label()],
            'video_url' => $this->videoPath ? Storage::url($this->videoPath) : null,
            'lesson_content' => $this->content,
            'sort_order' => $this->sortOrder,
            'task' => $this->when(
                $this->type === LessonType::TASK && $this->task !== null,
                fn() => (new TaskResource($this->task))->resolve(),
            ),
        ];
    }
}
