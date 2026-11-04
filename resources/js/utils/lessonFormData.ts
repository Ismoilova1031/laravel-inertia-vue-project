import type { LessonFormData } from "@/composables/useLessonForm";
import type { Lesson } from "@/types/lesson";

export function toLessonFormData(lesson: Lesson): LessonFormData {
    const task = lesson.task;

    return {
        title: lesson.title,
        description: lesson.description,
        sort_order: lesson.sort_order,
        lesson_type: lesson.lesson_type.value,
        video: undefined,
        lesson_content: lesson.lesson_content ?? "",
        task: task
            ? {
                  ...task,
                  task_type: task.task_type?.value ?? null,
                  questions: Array.isArray(task.questions)
                      ? task.questions.map((q) => ({
                            ...q,
                            question_type: q.question_type.value,
                        }))
                      : null,
              }
            : {
                  task_type: null,
                  allowed_file_extensions: null,
                  questions: null,
              },
    };
}
