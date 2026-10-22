<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentListForCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $student = $this->resource->student;

        return [
            'id' => $student->id()->value(),
            'name' => $student->name()->name(),
            'surname' => $student->name()->surname(),
            'fullName' => $student->name()->fullName(),
            'email' => $student->email()->value(),
            'username' => $student->username()->value(),
            'isActive' => $this->resource->enrollment->isActive(),
        ];
    }
}
