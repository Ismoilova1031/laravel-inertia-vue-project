<?php

namespace App\Http\Resources;

use App\Domain\Assessment\QuestionType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $question = $this->resource;

        return [
            'id' => $question->id(),
            'question' => $question->text(),
            'question_type' => [
                'value' => $question->type()->value,
                'label' => $question->type()->label(),
            ],
            'points' => $question->points()->value(),
            'correct_answer' => $question->correctAnswer(),
            'options' => $this->when(
                in_array($question->type(), [QuestionType::MULTIPLE_SELECT, QuestionType::SINGLE_CHOICE], true),
                fn () => QuestionOptionResource::collection($question->options())->resolve(),
            ),
        ];
    }
}
