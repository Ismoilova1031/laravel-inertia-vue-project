<?php

namespace App\Http\Requests;

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
}