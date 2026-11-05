<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $course = $this->resource->course;

        return [
            'id' => $course->id()->value(),
            'title' => $course->title()->value(),
            'description' => $course->description()->value(),
            'category' => [
                'value' => $course->category()->value,
                'label' => $course->category()->label(),
            ],
            'status' => [
                'value' => $course->status()->value,
                'label' => $course->status()->label(),
            ],
            'lessons' => LessonListResource::collection($this->resource->lessons)->resolve(),
            'students' => StudentListForCourseResource::collection($this->resource->members)->resolve(),
        ];
    }
}
