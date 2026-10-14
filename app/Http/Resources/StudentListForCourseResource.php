<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Enrollment\EnrollmentStatus;

class StudentListForCourseResource extends JsonResource
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
            'name' => $this->name,
            'surname' => $this->surname,
            'fullName' => "{$this->name} {$this->surname}",
            'email' => $this->email,
            'username' => $this->username,
               'isActive' => (int) $this->pivot->status === EnrollmentStatus::ACTIVE->value,
        ];
    }
}
