import z from "zod";

export const optionFormSchema = z.object({
    id: z.union([z.number(), z.string()]),
    text: z.string().min(1, { message: "Option text is required" }),
    is_correct: z.boolean(),
});

export type OptionForm = z.infer<typeof optionFormSchema>;
export type OptionFormData = z.input<typeof optionFormSchema>;
