<?php

namespace App\Http\Requests;

use App\Application\UseCase\Course\ChangeCourseStatusCommand;
use App\Domain\Course\CourseStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ChangeCourseStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(CourseStatus::class)],
        ];
    }

    public function toCommand(): ChangeCourseStatusCommand
    {
        return new ChangeCourseStatusCommand(
            id: (int) $this->route('course'),
            status: (int) $this->validated()['status'],
        );
    }
}
