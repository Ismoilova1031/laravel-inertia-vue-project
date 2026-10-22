<?php

namespace App\Http\Requests;

use App\Domain\Shared\UploadedFileData;
use App\Application\UseCase\Assessment\TaskData;
use App\Application\UseCase\Lesson\CreateLessonCommand;
use App\Application\UseCase\Lesson\UpdateLessonCommand;
use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'lesson_type' => ['required', 'integer', 'between:1,3'],
            'lesson_content' => ['nullable', 'string'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo', 'max:102400'],
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['sort_order'] = ['required', 'integer', 'min:1'];
        }

        return $rules;
    }

    public function toCreateCommand(): CreateLessonCommand
    {
        $d = $this->validated();

        return new CreateLessonCommand(
            courseId: (int) $this->route('course'),
            title: $d['title'],
            description: $d['description'],
            type: (int) $d['lesson_type'],
            content: $d['lesson_content'] ?? null,
            video: $this->videoFile(),
            task: $this->taskDto(),
        );
    }

    public function toUpdateCommand(): UpdateLessonCommand
    {
        $d = $this->validated();

        return new UpdateLessonCommand(
            id: (int) $this->route('lesson'),
            title: $d['title'],
            description: $d['description'],
            type: (int) $d['lesson_type'],
            sortOrder: (int) $d['sort_order'],
            content: $d['lesson_content'] ?? null,
            video: $this->videoFile(),
            task: $this->taskDto(),
        );
    }

    private function videoFile(): ?UploadedFileData
    {
        $file = $this->file('video');

        return $file ? new UploadedFileData($file->getRealPath(), $file->getClientOriginalExtension()) : null;
    }

    private function taskDto(): ?TaskData
    {
        $task = $this->input('task');

        if (! is_array($task) || empty($task['task_type'])) {
            return null;
        }

        return new TaskData(
            type: (int) $task['task_type'],
            fileExtensions: $task['allowed_file_extensions'] ?? null,
            questions: $task['questions'] ?? null,
        );
    }
}
