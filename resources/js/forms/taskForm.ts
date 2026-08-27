import z from "zod";
import { TaskTypes } from "../types/task-types";
import { questionFormSchema } from "./questionForm";
export const taskFormSchema = z
    .object({
        task_type: z.union([
            z.literal(TaskTypes.QUIZ),
            z.literal(TaskTypes.FILE_UPLOAD),
            z.literal(TaskTypes.DISCUSSION),
            z.literal(null),
        ]),

        deadline: z
            .string()
            .nullable(),

        file_extensions: z
            .array(z.string())
            .nullable(),

        questions: z
            .array(questionFormSchema)
            .nullable(),
    })
    .superRefine((data, ctx) => {

        if (
            data.task_type === TaskTypes.FILE_UPLOAD &&
            (!data.file_extensions || data.file_extensions.length === 0)
        ) {
            ctx.addIssue({
                code: "custom",
                path: ["file_extensions"],
                message: "File extensions are required for file upload tasks",
            });
        }

        if (
            data.task_type === TaskTypes.QUIZ &&
            (!data.questions || data.questions.length === 0)
        ) {
            ctx.addIssue({
                code: "custom",
                path: ["questions"],
                message: "At least one question is required",
            });
        }
    });

export type TaskForm = z.infer<typeof taskFormSchema>;
export type TaskFormData = z.input<typeof taskFormSchema>;
