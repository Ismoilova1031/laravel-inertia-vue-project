<?php

namespace App\Http\Resources;

use App\Domain\Assessment\Question;
use App\Domain\Assessment\Submission\StudentAnswer;
use App\Domain\Assessment\TaskType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionRowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $r = $this->resource;
        $s = $r->submission;
        $answers = [];
        foreach ($s->answers() as $a) {
            $answers[$a->questionId()] = $a;
        }

        return [
            'id' => $s->id()->value(),
            'attempt' => $s->attempt()->value(),
            'status' => ['value' => $s->status()->value, 'label' => $s->status()->label()],
            'score' => $s->score(),
            'maxScore' => $r->task->totalPoints(),
            'submittedAt' => $s->submittedAt()->format('Y-m-d H:i'),
            'student' => ['id' => $r->student->id()->value(), 'fullName' => $r->student->name()->fullName()],
            'lesson' => ['id' => $r->lesson->id()->value(), 'title' => $r->lesson->title()->value()],
            'taskType' => ['value' => $r->task->type()->value, 'label' => $r->task->type()->label()],
            'content' => $s->content(),
            'hasFile' => $s->filePath() !== null,
            'fileExtension' => $s->filePath() ? strtolower(pathinfo($s->filePath(), PATHINFO_EXTENSION)) : null,
            'teacherComment' => $s->teacherComment(),
            'answers' => $r->task->type() === TaskType::QUIZ
                ? array_map(fn(Question $q) => [
                    'questionId' => $q->id(),
                    'question' => $q->text(),
                    'questionType' => $q->type()->value,
                    'points' => $q->points()->value(),
                    'text' => ($answers[$q->id()] ?? null)?->text(),
                    'selected' => collect($q->options())
                        ->filter(fn($o) => in_array($o->id(), ($answers[$q->id()] ?? null)?->selectedOptionIds() ?? [], true))
                        ->map(fn($o) => $o->text())->values()->all(),
                    'score' => ($answers[$q->id()] ?? null)?->score(),
                    'teacherComment' => ($answers[$q->id()] ?? null)?->teacherComment(),
                    'needsGrading' => $q->type()->value === 4 && ($answers[$q->id()] ?? null)?->text() !== null,
                ], $r->task->questions())
                : [],
        ];
    }
}
