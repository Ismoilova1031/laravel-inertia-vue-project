<?php

namespace App\Http\Requests;

use App\Application\UseCase\Assessment\SubmitTaskCommand;
use App\Domain\Shared\UploadedFileData;
use Illuminate\Foundation\Http\FormRequest;

final class SubmitTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => ['nullable', 'array'],
            'answers.*.options' => ['nullable', 'array'],
            'answers.*.options.*' => ['integer'],
            'answers.*.text' => ['nullable', 'string', 'max:10000'],
            'text' => ['nullable', 'string', 'max:10000'],
            'file' => ['nullable', 'file', 'max:10240'],
        ];
    }

    public function toCommand(): SubmitTaskCommand
    {
        $file = $this->file('file');

        $answers = null;
        if ($this->has('answers')) {
            $answers = [];
            foreach ((array) $this->input('answers') as $questionId => $answer) {
                $answers[(int) $questionId] = [
                    'options' => array_map('intval', $answer['options'] ?? []),
                    'text' => $answer['text'] ?? null,
                ];
            }
        }

        return new SubmitTaskCommand(
            taskId: (int) $this->route('task'),
            studentId: (int) $this->route('student'),
            answers: $answers,
            text: $this->input('text'),
            file: $file ? new UploadedFileData($file->getRealPath(), $file->getClientOriginalExtension()) : null,
        );
    }
}