<?php

namespace App\Http\Resources;

use App\Domain\Lesson\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LearnLessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $v = $this->resource;
        $l = $v->lesson;
        $ref = fn (?Lesson $x) => $x ? ['id' => $x->id()->value(), 'title' => $x->title()->value()] : null;

        return [
            'id' => $l->id()->value(),
            'title' => $l->title()->value(),
            'description' => $l->description()->value(),
            'type' => ['value' => $l->type()->value, 'label' => $l->type()->label()],
            'content' => $l->content(),
            'videoUrl' => $l->videoPath() ? Storage::url($l->videoPath()) : null,
            'completed' => $v->completed,
            'course' => ['id' => $v->course->id()->value(), 'title' => $v->course->title()->value()],
            'previous' => $ref($v->previous),
            'next' => $ref($v->next),
        ];
    }
}