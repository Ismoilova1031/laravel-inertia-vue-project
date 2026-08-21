import z, { array }  from "zod";
import { TaskTypes } from "../types/taskTypes";
export const taskFormSchema = z
    .object({
        type: z.union([
            z.literal(TaskTypes.QUIZ),
            z.literal(TaskTypes.FILE_UPLOAD),
            z.literal(TaskTypes.DISCUSSION),
            z.literal(null)
        ]),
        deadline: z
            .string().nullable(),
        file_extensions: z
            .array(z.string())
            .nullable(),
        questions: array(z.any()).nullable()
    })
    .superRefine((data, ctx) => {
        if(data.type === TaskTypes.FILE_UPLOAD && !data.file_extensions) {
            ctx.addIssue({
                code: "custom",
                path: ["file_extensions"],
                message: "File extensions are required for file upload tasks",
            });
        }
    });

export type TaskForm = z.infer<typeof taskFormSchema>;
export type TaskFormData = z.input<typeof taskFormSchema>;
