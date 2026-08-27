import z from "zod";
import { optionFormSchema } from "./optionForm";

export const questionFormSchema = z
    .object({
        id: z.union([z.number(), z.string()]).optional(),
        question: z.string().min(1, { message: "Question text is required" }),
        question_type: z.union([
            z.literal(1),
            z.literal(2),
            z.literal(3),
            z.literal(4),
        ]),
        points: z
            .number()
            .min(0, { message: "Points must be a non-negative number" }),
        options: z.array(optionFormSchema).nullable(),
        correct_answer: z.string().nullable(),
    })
    .superRefine((data, ctx) => {
        if (
            (data.question_type === 1 ||
                data.question_type === 2) &&
            (!data.options || data.options.length < 2)
        ) {
            ctx.addIssue({
                code: "custom",
                path: ["options"],
                message:
                    "At least two options are required for choice questions",
            });
        }

        if (
            (data.question_type === 1 ||
                data.question_type === 2) &&
            data.options?.every((o) => !o.is_correct)
        ) {
            ctx.addIssue({
                code: "custom",
                path: ["options"],
                message:
                    "At least one option must be marked as correct for choice questions",
            });
        }

        if (data.question_type === 3 && !data.correct_answer?.trim()) {
            ctx.addIssue({
                code: "custom",
                path: ["correct_answer"],
                message:
                    "A correct answer is required for short answer questions",
            });
        }
    });

export type QuestionForm = z.infer<typeof questionFormSchema>;
export type QuestionFormData = z.input<typeof questionFormSchema>;
