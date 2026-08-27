<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\TaskType;
use App\Http\Resources\QuestionResource;

class TaskResource extends JsonResource
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
            'task_type' => [
                'value' => $this->task_type->value,
                'label' => $this->task_type->label(),
            ],
            'deadline' => $this->deadline?->format('Y-m-d\TH:i'),
            'allowed_file_extensions' => $this->allowed_file_extensions,
            'questions' => $this->when(
                $this->task_type === TaskType::QUIZ,
                fn() => QuestionResource::collection($this->questions)->resolve(),
            ),
        ];
    }
}
