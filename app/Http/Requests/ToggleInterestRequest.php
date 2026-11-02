<?php

namespace App\Http\Requests;

use App\Domain\CourseInterest\InterestType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ToggleInterestRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(InterestType::class)],
            'enabled' => ['required', 'boolean'],
        ];
    }
}