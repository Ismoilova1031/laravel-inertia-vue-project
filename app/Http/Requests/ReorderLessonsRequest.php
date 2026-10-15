<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Application\UseCase\Lesson\ReorderLessonsCommand;

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
        $positions = [];
        foreach ($this->validated('lessons') as $lesson) {
            $positions[(int) $lesson['id']] = (int) $lesson['sort_order'];
        }

        return new ReorderLessonsCommand((int) $this->route('course'), $positions);
    }
}
