import z from "zod";
import { useForm } from "@inertiajs/vue3";
import { LessonType } from "../types/lesson-types";
import { taskFormSchema } from "./taskForm";
import LessonController from "../actions/App/Http/Controllers/LessonController";
export const lessonFormSchema = z
    .object({
        title: z
            .string()
            .min(1, {
                message: "Title is required",
            })
            .max(255, {
                message: "Title must be less than 255 characters",
            }),

        description: z
            .string()
            .min(1, {
                message: "Description is required",
            })
            .max(255, {
                message: "Description must be less than 255 characters",
            }),

        sort_order: z
            .number()
            .min(1, {
                message: "Sort Order is required",
            })
            .max(100, {
                message: "Sort Order must be less than 100",
            }),

        lesson_type: z.union([
            z.literal(LessonType.VIDEO),
            z.literal(LessonType.TEXT),
            z.literal(LessonType.TASK),
        ]),

        video: z
            .instanceof(File)
            .nullable()
            .refine((file) => !file || file.size <= 100 * 1024 * 1024, {
                message: "Video must be less than 100MB",
            }),

        content: z.string(),

        task: taskFormSchema,
    })
    .superRefine((data, ctx) => {
        if (data.lesson_type === LessonType.VIDEO && !data.video) {
            ctx.addIssue({
                code: "custom",
                path: ["video"],
                message: "Video is required",
            });
        }

        if (
            data.lesson_type === LessonType.TEXT &&
            data.content.replace(/<[^>]*>/g, "").trim() === ""
        ) {
            ctx.addIssue({
                code: "custom",
                path: ["content"],
                message: "Content is required",
            });
        }

        if (data.lesson_type === LessonType.TASK) {
            if (!data.task?.type) {
                ctx.addIssue({
                    code: "custom",
                    path: ["task", "type"],
                    message: "Task type is required",
                });
            }
            if (!data.task?.deadline) {
                ctx.addIssue({
                    code: "custom",
                    path: ["task", "deadline"],
                    message: "Deadline is required",
                });
            }
        }
    });

export type LessonForm = z.infer<typeof lessonFormSchema>;
type LessonFormData = z.input<typeof lessonFormSchema>;

export function useLessonForm(
    courseId: number,
    initialData?: LessonFormData,
    lessonId?: number,
) {
    console.log("Initial Data:", initialData); // Debugging line
    const form = useForm<LessonFormData>({
        title: initialData?.title ?? "",
        description: initialData?.description ?? "",
        sort_order: initialData?.sort_order ?? 0,
        lesson_type: initialData?.lesson_type ?? LessonType.VIDEO,
        video: initialData?.video ?? null,
        content: initialData?.content ?? "",

        task: initialData?.task ?? {
            type: null,
            deadline: null,
            file_extensions: null,
            questions: null,
        },
    });

    function validate(): boolean {
        form.clearErrors();
        const result = lessonFormSchema.safeParse(form.data());

        if (!result.success) {
            const errors: Record<string, string> = {};

            for (const issue of result.error.issues) {
                const path = issue.path.join(".");
                if (path && !errors[path]) {
                    errors[path] = issue.message;
                }
            }

            form.setError(
                errors as Partial<Record<keyof typeof form.errors, string>>,
            );
            return false;
        }

        return true;
    }

    function submit() {
        if (!validate()) {
            return;
        }

        if (lessonId) {
            form.put(
                LessonController.update({
                    course: courseId,
                    lesson: lessonId,
                }).url,
                {
                    preserveScroll: true,
                },
            );
        } else {
            form.post(
                LessonController.store({
                    course: courseId,
                }).url,
                {
                    preserveScroll: true,
                },
            );
        }
    }
    return {
        form,
        validate,
        submit,
    };
}

export type LessonFormInstance = ReturnType<typeof useLessonForm>["form"];
