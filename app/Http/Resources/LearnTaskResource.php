<?php

namespace App\Http\Resources;

use App\Domain\Assessment\Question;
use App\Domain\Assessment\QuestionOption;
use App\Domain\Assessment\Submission\StudentAnswer;
use App\Domain\Assessment\Submission\TaskSubmission;
use App\Domain\Assessment\TaskType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearnTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $value = $this->resource;
        $task = $value->task;
        $reveal = $value->revealAnswers;

        return [
            'id' => $task->id()->value(),
            'type' => ['value' => $task->type()->value, 'label' => $task->type()->label()],
            'allowedExtensions' => $task->allowedExtensions()?->values() ?? [],
            'questions' => $task->type() === TaskType::QUIZ
                ? array_map(fn (Question $question) => [
                    'id' => $question->id(),
                    'text' => $question->text(),
                    'type' => ['value' => $question->type()->value, 'label' => $question->type()->label()],
                    'points' => $question->points()->value(),
                    'options' => array_map(fn (QuestionOption $option) => array_filter([
                        'id' => $option->id(),
                        'text' => $option->text(),
                        'isCorrect' => $reveal ? $option->isCorrect() : null,
                    ], fn ($x) => $x !== null), $question->options()),
                    'correctAnswer' => $reveal ? $question->correctAnswer() : null,
                ], $task->questions())
                : [],
            'canSubmit' => $value->canSubmit,
            'blockedReason' => $value->blockedReason,
            'attemptsLeft' => $value->attemptsLeft,
            'bestScore' => $value->bestScore,
            'maxScore' => $value->maxScore,
            'revealAnswers' => $reveal,
            'submissions' => array_map(fn (TaskSubmission $submission) => [
                'attempt' => $submission->attempt()->value(),
                'status' => ['value' => $submission->status()->value, 'label' => $submission->status()->label()],
                'score' => $submission->score(),
                'content' => $submission->content(),
                'hasFile' => $submission->filePath() !== null,
                'teacherComment' => $submission->teacherComment(),
                'submittedAt' => $submission->submittedAt()->format('Y-m-d H:i'),
                'answers' => array_map(fn (StudentAnswer $answer) => [
                    'questionId' => $answer->questionId(),
                    'selectedOptionIds' => $answer->selectedOptionIds(),
                    'text' => $answer->text(),
                    'score' => $answer->score(),
                    'teacherComment' => $answer->teacherComment(),
                ], $submission->answers()),
            ], $value->submissions),
        ];
    }
}