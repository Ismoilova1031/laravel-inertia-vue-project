import type { LessonFormData } from "@/composables/useLessonForm";
import { emptyTaskForm } from "@/composables/useTaskForm";
import type { Lesson, LessonType } from "@/types/lesson";
import type { QuestionType } from "@/types/question";
import type { TaskType } from "@/types/task";

export function toLessonFormData(lesson: Lesson): LessonFormData {
    const task = lesson.task;

    return {
        title: lesson.title,
        description: lesson.description,
        sort_order: lesson.sort_order,
        lesson_type: lesson.lesson_type.value as LessonType,
        video: undefined,
        lesson_content: lesson.lesson_content ?? "",
        task: task
            ? {
                  task_type: task.task_type.value as TaskType,
                  allowed_file_extensions: task.allowed_file_extensions,
                  questions:
                      task.questions?.map((q) => ({
                          id: q.id,
                          question: q.question,
                          question_type: q.question_type.value as QuestionType,
                          points: q.points,
                          correct_answer: q.correct_answer,
                          options: q.options?.map((o) => ({ id: o.id, option: o.option, is_correct: o.is_correct })) ?? [],
                      })) ?? null,
              }
            : emptyTaskForm(),
    };
}