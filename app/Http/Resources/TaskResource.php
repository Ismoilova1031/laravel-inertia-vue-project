<?php

namespace App\Http\Resources;

use App\Domain\Assessment\TaskType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $task = $this->resource;

        return [
            'id' => $task->id()->value(),
            'task_type' => [
                'value' => $task->type()->value,
                'label' => $task->type()->label(),
            ],
            'allowed_file_extensions' => $task->allowedFileExtensions()?->values(),
            'questions' => $this->when(
                $task->type() === TaskType::QUIZ,
                fn () => QuestionResource::collection($task->questions())->resolve(),
            ),
        ];
    }
}
