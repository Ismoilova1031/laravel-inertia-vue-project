<?php

namespace App\Http\Requests;

use App\Application\Enrollment\EnrollStudent\EnrollStudentCommand;
use Illuminate\Foundation\Http\FormRequest;

final class EnrollStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Studentni tanlang.',
            'student_id.exists'   => 'Bunday student topilmadi.',
        ];
    }

    public function toCommand(): EnrollStudentCommand
    {
        return new EnrollStudentCommand(
            courseId: (int) $this->route('course'),
            studentId: (int) $this->validated()['student_id'],
        );
    }
}
