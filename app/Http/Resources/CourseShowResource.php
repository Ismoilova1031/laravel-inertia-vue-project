<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LessonListResource;
use App\Http\Resources\StudentListResource;

class CourseShowResource extends JsonResource
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
            'category' => [
                'value' => $this->category->value,
                'label' => $this->category->label(),
            ],
            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
            ],
            'lessons' => LessonListResource::collection($this->lessons)->resolve(),
            'students' => StudentListResource::collection($this->students)->resolve(),
        ];
    }
}
