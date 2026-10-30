import z from "zod";
import LessonController from "@/actions/App/Http/Controllers/LessonController";
import { LessonType } from "@/types/lesson";
import { taskFormSchema } from "./useTaskForm";
import { useZodForm } from "./useZodForm";

export const lessonFormSchema = z
    .object({
        title: z
            .string()
            .min(1, { message: "Title is required" })
            .max(255, { message: "Title must be less than 255 characters" }),

        description: z
            .string()
            .min(1, { message: "Description is required" })
            .max(255, { message: "Description must be less than 255 characters" }),

        sort_order: z.number().min(1, { message: "Position must be at least 1" }),

        lesson_type: z.union([
            z.literal(LessonType.VIDEO),
            z.literal(LessonType.TEXT),
            z.literal(LessonType.TASK),
        ]),

        video: z
            .instanceof(File)
            .optional()
            .refine((file) => !file || file.size <= 100 * 1024 * 1024, {
                message: "Video must be less than 100MB",
            }),

        lesson_content: z.string(),

        task: taskFormSchema,
    })
    .superRefine((data, ctx) => {
        if (data.lesson_type === LessonType.TEXT && data.lesson_content.replace(/<[^>]*>/g, "").trim() === "") {
            ctx.addIssue({ code: "custom", path: ["lesson_content"], message: "Content is required" });
        }

        if (data.lesson_type === LessonType.TASK && !data.task?.task_type) {
            ctx.addIssue({ code: "custom", path: ["task", "task_type"], message: "Task type is required" });
        }
    });

export type LessonFormData = z.input<typeof lessonFormSchema>;

export function useLessonForm(
    courseId: number,
    initialData?: LessonFormData,
    lessonId?: number,
    existingVideoUrl?: string | null,
) {
    const { form, validate: validateSchema } = useZodForm(lessonFormSchema, {
        title: initialData?.title ?? "",
        description: initialData?.description ?? "",
        sort_order: initialData?.sort_order ?? 1,
        lesson_type: initialData?.lesson_type ?? LessonType.VIDEO,
        video: initialData?.video ?? undefined,
        lesson_content: initialData?.lesson_content ?? "",
        task: initialData?.task ?? { task_type: null, allowed_file_extensions: null, questions: null },
    });

    function validate(): boolean {
        if (!validateSchema()) return false;

        const data = form.data();
        if (data.lesson_type === LessonType.VIDEO && !data.video && !existingVideoUrl) {
            form.setError("video", "Video is required");
            return false;
        }

        return true;
    }

    function submit() {
        if (!validate()) return;

        const options = { preserveScroll: true };

        if (lessonId) {
            form.put(LessonController.update({ course: courseId, lesson: lessonId }).url, options);
        } else {
            form.post(LessonController.store({ course: courseId }).url, options);
        }
    }

    return { form, validate, submit };
}

export type LessonFormInstance = ReturnType<typeof useLessonForm>["form"];