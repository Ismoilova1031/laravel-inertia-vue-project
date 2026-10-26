<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ReviewSubmissionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'accept' => ['required', 'boolean'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ];
    }
}