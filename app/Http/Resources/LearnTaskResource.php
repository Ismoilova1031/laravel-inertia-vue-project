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
        $v = $this->resource;
        $task = $v->task;
        $reveal = $v->revealAnswers;

        return [
            'id' => $task->id()->value(),
            'type' => ['value' => $task->type()->value, 'label' => $task->type()->label()],
            'allowedExtensions' => $task->allowedExtensions()?->values() ?? [],
            'questions' => $task->type() === TaskType::QUIZ
                ? array_map(fn (Question $q) => [
                    'id' => $q->id(),
                    'text' => $q->text(),
                    'type' => ['value' => $q->type()->value, 'label' => $q->type()->label()],
                    'points' => $q->points()->value(),
                    'options' => array_map(fn (QuestionOption $o) => array_filter([
                        'id' => $o->id(),
                        'text' => $o->text(),
                        'isCorrect' => $reveal ? $o->isCorrect() : null,
                    ], fn ($x) => $x !== null), $q->options()),
                    'correctAnswer' => $reveal ? $q->correctAnswer() : null,
                ], $task->questions())
                : [],
            'canSubmit' => $v->canSubmit,
            'blockedReason' => $v->blockedReason,
            'attemptsLeft' => $v->attemptsLeft,
            'bestScore' => $v->bestScore,
            'maxScore' => $v->maxScore,
            'revealAnswers' => $reveal,
            'submissions' => array_map(fn (TaskSubmission $s) => [
                'attempt' => $s->attempt()->value(),
                'status' => ['value' => $s->status()->value, 'label' => $s->status()->label()],
                'score' => $s->score(),
                'content' => $s->content(),
                'hasFile' => $s->filePath() !== null,
                'teacherComment' => $s->teacherComment(),
                'submittedAt' => $s->submittedAt()->format('Y-m-d H:i'),
                'answers' => array_map(fn (StudentAnswer $a) => [
                    'questionId' => $a->questionId(),
                    'selectedOptionIds' => $a->selectedOptionIds(),
                    'text' => $a->text(),
                    'score' => $a->score(),
                    'teacherComment' => $a->teacherComment(),
                ], $s->answers()),
            ], $v->submissions),
        ];
    }
}