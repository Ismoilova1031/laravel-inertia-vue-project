import z from "zod";
import { TaskTypes } from "@/types/task";

export const optionFormSchema = z.object({
    id: z.union([z.number(), z.string()]),
    option: z.string().min(1, { message: "Option text is required" }),
    is_correct: z.boolean(),
});

export type OptionFormData = z.input<typeof optionFormSchema>;

export const questionFormSchema = z
    .object({
        id: z.union([z.number(), z.string()]).optional(),
        question: z.string().min(1, { message: "Question text is required" }),
        question_type: z.union([z.literal(1), z.literal(2), z.literal(3), z.literal(4)]),
        points: z.number().min(0, { message: "Points must be a non-negative number" }),
        options: z.array(optionFormSchema).optional(),
        correct_answer: z.string().nullable(),
    })
    .superRefine((data, ctx) => {
        const isChoice = data.question_type === 1 || data.question_type === 2;

        if (isChoice && (!data.options || data.options.length < 2)) {
            ctx.addIssue({
                code: "custom",
                path: ["options"],
                message: "At least two options are required for choice questions",
            });
        }

        if (isChoice && data.options?.every((o) => !o.is_correct)) {
            ctx.addIssue({
                code: "custom",
                path: ["options"],
                message: "At least one option must be marked as correct for choice questions",
            });
        }

        if (data.question_type === 3 && !data.correct_answer?.trim()) {
            ctx.addIssue({
                code: "custom",
                path: ["correct_answer"],
                message: "A correct answer is required for short answer questions",
            });
        }
    });

export type QuestionFormData = z.input<typeof questionFormSchema>;

export const taskFormSchema = z
    .object({
        task_type: z.union([
            z.literal(TaskTypes.QUIZ),
            z.literal(TaskTypes.FILE_UPLOAD),
            z.literal(TaskTypes.DISCUSSION),
            z.literal(null),
        ]),
        allowed_file_extensions: z.array(z.string()).nullable(),
        questions: z.array(questionFormSchema).nullable(),
    })
    .superRefine((data, ctx) => {
        if (
            data.task_type === TaskTypes.FILE_UPLOAD &&
            (!data.allowed_file_extensions || data.allowed_file_extensions.length === 0)
        ) {
            ctx.addIssue({
                code: "custom",
                path: ["allowed_file_extensions"],
                message: "File extensions are required for file upload tasks",
            });
        }

        if (data.task_type === TaskTypes.QUIZ && (!data.questions || data.questions.length === 0)) {
            ctx.addIssue({
                code: "custom",
                path: ["questions"],
                message: "At least one question is required",
            });
        }
    });

export type TaskFormData = z.input<typeof taskFormSchema>;