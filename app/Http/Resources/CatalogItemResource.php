<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $item = $this->resource;
        $course = $item->course;

        return [
            'id' => $course->id()->value(),
            'title' => $course->title()->value(),
            'description' => $course->description()->value(),
            'category' => $course->category()->label(),
            'liked' => $item->liked,
            'planned' => $item->planned,
            'enrolled' => $item->enrolled,
            'rating' => [
                'average' => $item->rating->average,
                'count' => $item->rating->count,
            ],
        ];
    }
}