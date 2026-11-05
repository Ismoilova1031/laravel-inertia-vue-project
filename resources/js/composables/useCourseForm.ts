import z from "zod";
import CourseController from "@/actions/App/Http/Controllers/CourseController";
import { useZodForm } from "./useZodForm";

export const courseFormSchema = z.object({
    title: z
        .string()
        .min(1, { message: "Title is required" })
        .max(255, { message: "Title must be less than 255 characters" }),

    description: z
        .string()
        .min(1, { message: "Description is required" })
        .max(255, { message: "Description must be less than 255 characters" }),

    category: z
        .number()
        .min(0)
        .max(9)
        .nullable()
        .refine((value) => value !== null, { message: "Category is required" }),

    status: z.number().min(0).max(2).nullable().optional(),
});

export type CourseFormData = z.input<typeof courseFormSchema>;

export function useCourseForm(initialData?: CourseFormData, courseId?: number) {
    const { form, validate } = useZodForm(courseFormSchema, {
        title: initialData?.title ?? "",
        description: initialData?.description ?? "",
        category: initialData?.category ?? null,
        status: initialData?.status ?? null,
    });

    function submit(): void {
        if (!validate()) return;

        if (courseId) {
            form.put(CourseController.update(courseId).url);
            return;
        }

        form.post(CourseController.store().url, {
            onSuccess: () => form.reset(),
        });
    }

    return { form, validate, submit };
}

export type CourseFormInstance = ReturnType<typeof useCourseForm>["form"];
