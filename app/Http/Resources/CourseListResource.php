<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $course = $this->resource;

        return [
            'id' => $course->id()->value(),
            'title' => $course->title()->value(),
            'category' => $course->category()->label(),
            'status' => [
                'value' => $course->status()->value,
                'label' => $course->status()->label(),
            ],
            'description' => $course->description()->value(),
        ];
    }
}
