<?php

namespace App\Http\Requests;

use App\Application\UseCase\Course\CreateCourseCommand;
use App\Application\UseCase\Course\UpdateCourseCommand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'integer', 'between:0,9'],
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['status'] = ['required', 'integer', 'between:0,2'];
        }

        return $rules;
    }

    public function toCreateCommand(): CreateCourseCommand
    {
        $d = $this->validated();

        return new CreateCourseCommand(
            title: $d['title'],
            description: $d['description'],
            category: (int) $d['category'],
        );
    }

    public function toUpdateCommand(): UpdateCourseCommand
    {
        $d = $this->validated();

        return new UpdateCourseCommand(
            id: (int) $this->route('course'),
            title: $d['title'],
            description: $d['description'],
            category: (int) $d['category'],
            status: (int) $d['status'],
        );
    }
}
