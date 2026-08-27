<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\QuestionType;
use App\Http\Resources\QuestionOptionResource;

class QuestionResource extends JsonResource
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
            'question' => $this->question,
            'question_type' => [
                'value' => $this->question_type->value,
                'label' => $this->question_type->label(),
            ],
            'points' => $this->points,
            'sort_order' => $this->sort_order,
            'correct_answer' => $this->correct_answer,
            'options' => $this->when(
                in_array($this->question_type, [QuestionType::MULTIPLE_SELECT, QuestionType::SINGLE_CHOICE]),
                QuestionOptionResource::collection($this->options)->resolve(),
            ),
        ];
    }
}
