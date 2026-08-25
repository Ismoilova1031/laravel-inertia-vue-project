<?php

namespace App\Http\Controllers;

use App\Enums\LessonType;
use App\Http\Requests\LessonRequest;
use Inertia\Inertia;
use App\Contracts\UseCases\CreateLessonUseCaseInterface;
use App\Contracts\UseCases\DeleteLessonUseCaseInterface;
use App\Dtos\LessonDto;
use App\Models\Course;
use App\Models\Lesson;
use App\Http\Requests\ReorderLessonsRequest;
use App\UseCases\ReorderLessonsUseCase;

class LessonController extends Controller
{
    
    public function __construct(
        private CreateLessonUseCaseInterface $createLessonUseCase,
        private ReorderLessonsUseCase $reorderLessonsUseCase,
        private DeleteLessonUseCaseInterface $deleteLessonUseCase
    ){}

    public function create(Course $course)
    {
        return Inertia::render('Lessons/Create', [
            'course' => $course,
            'types' => collect(LessonType::cases())
                ->map(fn (LessonType $type) => [
                    'label' => $type->label(),
                    'value' => $type->value,
                ]),
        ]);
    }

   public function store(Course $course, LessonRequest $request)
    {
        $validated = $request->validated();
        if(!$validated) {
            return back()->withErrors($request->errors());
        }
        $dto = new LessonDto(
            title: $request->title,
            description: $request->description,
            content: $request->content,
            lesson_type: $request->lesson_type,
            sort_order: $request->sort_order,
            course_id: $course->id,
            video: $request->file('video'),
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
}