<?php

namespace App\Http\Requests;

use App\Application\UseCase\Lesson\ReorderLessonsCommand;
use Illuminate\Foundation\Http\FormRequest;

class ReorderLessonsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lessons' => ['required', 'array'],
            'lessons.*.id' => ['required', 'integer', 'exists:lessons,id'],
            'lessons.*.sort_order' => ['required', 'integer', 'min:1'],
        ];
    }

    public function toCommand(): ReorderLessonsCommand
    {
        $lessons = $this->validated('lessons');
        usort($lessons, fn ($a, $b) => $a['sort_order'] <=> $b['sort_order']);

        return new ReorderLessonsCommand(
            (int) $this->route('course'),
            array_map(fn ($l) => (int) $l['id'], $lessons),
        );
    }
}
