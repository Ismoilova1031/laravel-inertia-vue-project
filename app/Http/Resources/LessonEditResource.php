<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\LessonType;
use App\Http\Resources\TaskResource;

class LessonEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lesson = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'lesson_type' => [
                'value' => $this->lesson_type->value,
                'label' => $this->lesson_type->label(),
            ],
            'video_url' => $this->video_url,
            'sort_order' => $this->sort_order,
            'task' => $this->when(
                $this->lesson_type === LessonType::TASK,
                fn() => new TaskResource($this->task)->resolve(),
            ),
        ];
        return $lesson;
    }
}
