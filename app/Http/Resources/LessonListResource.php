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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'lessonType' => [
                'value' => $this->lesson_type->value,
                'label' => $this->lesson_type->label(),
            ],
            'lessonContent' => $this->lesson_content,
            'videoUrl' => $this->video_url,
            'sortOrder' => $this->sort_order,
        ];
    }
}
