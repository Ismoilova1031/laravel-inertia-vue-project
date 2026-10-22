<?php

// app/Http/Controllers/LessonController.php

namespace App\Http\Controllers;

use App\Application\UseCase\Course\GetCourseUseCase;
use App\Application\UseCase\Lesson\CreateLessonUseCase;
use App\Application\UseCase\Lesson\DeleteLessonUseCase;
use App\Application\UseCase\Lesson\GetLessonUseCase;
use App\Application\UseCase\Lesson\ReorderLessonsUseCase;
use App\Application\UseCase\Lesson\UpdateLessonUseCase;
use App\Domain\Assessment\Exception\AssessmentRuleViolation;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\Exception\LessonTaskRequiredException;
use App\Domain\Lesson\LessonType;
use App\Http\Requests\LessonRequest;
use App\Http\Requests\ReorderLessonsRequest;
use App\Http\Resources\LessonEditResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LessonController extends Controller
{
    public function __construct(
        private readonly CreateLessonUseCase $createLesson,
        private readonly UpdateLessonUseCase $updateLesson,
        private readonly DeleteLessonUseCase $deleteLesson,
        private readonly ReorderLessonsUseCase $reorderLessons,
        private readonly GetLessonUseCase $getLesson,
        private readonly GetCourseUseCase $getCourse,
    ) {}

    public function create(int $course): Response
    {
        return Inertia::render('Lessons/Create', [
            'course' => $this->courseProps($course),
            'types' => $this->types(),
        ]);
    }

    public function store(LessonRequest $request, int $course): RedirectResponse
    {
        try {
            $this->createLesson->execute($request->toCreateCommand());
        } catch (CourseNotFoundException) {
            abort(404);
        } catch (LessonTaskRequiredException|AssessmentRuleViolation $e) {
            throw ValidationException::withMessages(['task.task_type' => $e->getMessage()]);
        }

        return to_route('courses.show', ['course' => $course]);
    }

    public function edit(int $course, int $lesson): Response
    {
        try {
            $view = $this->getLesson->execute($lesson);
        } catch (LessonNotFoundException) {
            abort(404);
        }

        return Inertia::render('Lessons/Edit', [
            'course' => $this->courseProps($course),
            'lesson' => LessonEditResource::make($view)->resolve(),
            'types' => $this->types(),
        ]);
    }

    public function update(LessonRequest $request, int $course, int $lesson): RedirectResponse
    {
        try {
            $this->updateLesson->execute($request->toUpdateCommand());
        } catch (LessonNotFoundException|CourseNotFoundException) {
            abort(404);
        } catch (LessonTaskRequiredException|AssessmentRuleViolation $e) {
            throw ValidationException::withMessages(['task.task_type' => $e->getMessage()]);
        }

        return to_route('courses.show', ['course' => $course]);
    }

    public function destroy(int $course, int $lesson): RedirectResponse
    {
        try {
            $this->deleteLesson->execute($lesson);
        } catch (LessonNotFoundException) {
            abort(404);
        }

        return to_route('courses.show', ['course' => $course]);
    }

    public function reorder(ReorderLessonsRequest $request, int $course): RedirectResponse
    {
        try {
            $this->reorderLessons->execute($request->toCommand());
        } catch (LessonNotFoundException) {
            abort(404);
        }

        return back();
    }

    private function courseProps(int $id): array
    {
        try {
            $detail = $this->getCourse->execute($id);
        } catch (CourseNotFoundException) {
            abort(404);
        }

        return [
            'id' => $detail->course->id()->value(),
            'title' => $detail->course->title()->value(),
            'description' => $detail->course->description()->value(),
            'lessonsCount' => count($detail->lessons),
        ];
    }

    private function types(): array
    {
        return collect(LessonType::cases())
            ->map(fn (LessonType $t) => ['label' => $t->label(), 'value' => $t->value])
            ->all();
    }
}
