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
            'lessonType' => ['value' => $this->type->value, 'label' => $this->type->label()],
            'lessonContent' => $this->content,
            'videoUrl' => $this->videoPath,
            'sortOrder' => $this->sortOrder,
        ];
    }
}
