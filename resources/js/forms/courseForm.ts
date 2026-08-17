import z from "zod";
import { useForm } from "@inertiajs/vue3";

export const courseFormSchema = z.object({
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

    category: z
        .number()
        .min(0, {
            message: "Category is required",
        })
        .max(9)
        .nullable()
        .refine((val) => val !== null, {
            message: "Category is required",
        }),

    status: z
        .number()
        .min(0, {
            message: "Status is required",
        })
        .max(2)
        .nullable()
        .refine((val) => val !== null, {
            message: "Status is required",
        }),
});

export type CourseForm = z.infer<typeof courseFormSchema>;
type CourseFormData = z.input<typeof courseFormSchema>;

export function useCourseForm(
    initialData?: CourseFormData,
    courseId?: number,
) {
    const form = useForm<CourseFormData>({
        title: initialData?.title ?? "",
        description: initialData?.description ?? "",
        category: initialData?.category ?? null,
        status: initialData?.status ?? null,
    });

    function validate(): boolean {
        form.clearErrors();

        const result = courseFormSchema.safeParse(form.data());

        if (!result.success) {
            result.error.issues.forEach((issue) => {
                const fieldName = issue.path[0] as keyof CourseFormData;

                form.setError(fieldName, issue.message);
            });

            return false;
        }

        return true;
    }

    function submit(): void {
        if (!validate()) {
            return;
        }

        if (courseId) {
            form.put(`/courses/${courseId}`, {
                onSuccess: () => {
                    form.reset();
                },
            });

            return;
        }

        form.post("/courses", {
            onSuccess: () => {
                form.reset();
            },
        });
    }

    return {
        form,
        submit,
        validate,
    };
}

export type CourseFormInstance = ReturnType<typeof useCourseForm>["form"];