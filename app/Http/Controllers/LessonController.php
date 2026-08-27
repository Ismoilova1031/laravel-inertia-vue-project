<?php

namespace App\Http\Controllers;

use App\Enums\LessonType;
use App\Http\Requests\LessonRequest;
use Inertia\Inertia;
use App\Contracts\UseCases\CreateLessonUseCaseInterface;
use App\Contracts\UseCases\DeleteLessonUseCaseInterface;
use App\Contracts\UseCases\ReorderLessonsUseCaseInterface;
use App\Contracts\UseCases\UpdateLessonUseCaseInterface;
use App\Dtos\LessonDto;
use App\Models\Course;
use App\Models\Lesson;
use App\Dtos\TaskDto;
use App\Enums\TaskType;
use App\Http\Requests\ReorderLessonsRequest;
use App\Http\Resources\LessonEditResource;

class LessonController extends Controller
{

    public function __construct(
        private CreateLessonUseCaseInterface $createLessonUseCase,
        private ReorderLessonsUseCaseInterface $reorderLessonsUseCase,
        private DeleteLessonUseCaseInterface $deleteLessonUseCase,
        private UpdateLessonUseCaseInterface $updateLessonUseCase
    ) {}

    public function create(Course $course)
    {
        return Inertia::render('Lessons/Create', [
            'course' => $course,
            'types' => collect(LessonType::cases())
                ->map(fn(LessonType $type) => [
                    'label' => $type->label(),
                    'value' => $type->value,
                ]),
        ]);
    }

    public function store(Course $course, LessonRequest $request)
    {
        $validated = $request->validated();
        if (!$validated) {
            return back()->withErrors($request->errors());
        }
        $dto = new LessonDto(
            title: $request->title,
            description: $request->description,
            lesson_content: $request->lesson_content,
            lesson_type: $request->lesson_type,
            sort_order: $request->sort_order,
            course_id: $course->id,
            video: $request->file('video'),
            tasks: $request->task ? new TaskDto(
                type: TaskType::fromValue($request->task['task_type']),
                deadline: $request->task['deadline'],
                file_extensions: $request->task['file_extensions'] ?? null,
                questions: $request->task['questions'] ?? null
            ) : null
        );
        $lesson = $this->createLessonUseCase->execute($dto);
        return redirect()->route('courses.show', ['course' => $lesson->course_id]);
    }

    public function reorder(
        ReorderLessonsRequest $request,
    ) {
        $this->reorderLessonsUseCase->execute(
            $request->validated('lessons')
        );

        return back();
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $this->deleteLessonUseCase->execute($lesson);

        return redirect()->route('courses.show', ['course' => $course]);
    }
    public function edit(Course $course, Lesson $lesson)
    {
        return Inertia::render('Lessons/Edit', [
            'course' => $course,
            'lesson' => LessonEditResource::make($lesson)->resolve(),
            'types' => collect(LessonType::cases())
                ->map(fn(LessonType $type) => [
                    'label' => $type->label(),
                    'value' => $type->value,
                ]),
        ]);
    }

    public function update(LessonRequest $request, Course $course, Lesson $lesson)
    {
        $validated = $request->validated();
        if (!$validated) {
            return back()->withErrors($request->errors());
        }
        $validated['course_id'] = $course->id;
        $dto = LessonDto::fromArray($validated);
        $lesson = $this->updateLessonUseCase->execute($lesson, $dto);
        return redirect()->route('courses.show', [
            'course' => $course->id,
        ]);
    }
}
