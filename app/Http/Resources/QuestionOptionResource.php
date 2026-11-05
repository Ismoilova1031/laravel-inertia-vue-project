<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $option = $this->resource;

        return [
            'id' => $option->id(),
            'option' => $option->option(),
            'is_correct' => $option->isCorrect(),
        ];
    }
}
