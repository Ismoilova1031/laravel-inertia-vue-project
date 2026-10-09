
import z from "zod";
import { useForm } from "@inertiajs/vue3";
import { store, update } from '../actions/App/Http/Controllers/StudentController';

export const studentFormSchema = z
    .object({
        name: z
            .string()
            .min(1, {
                message: "Ism kiritilishi shart.",
            })
            .max(255, {
                message: "Ism 255 ta belgidan oshmasligi kerak.",
            }),

        surname: z
            .string()
            .min(1, {
                message: "Familiya kiritilishi shart.",
            })
            .max(255, {
                message: "Familiya 255 ta belgidan oshmasligi kerak.",
            }),

        email: z
            .string()
            .min(1, {
                message: "Email kiritilishi shart.",
            })
            .email({
                message: "Email manzili noto‘g‘ri formatda.",
            })
            .max(255, {
                message: "Email 255 ta belgidan oshmasligi kerak.",
            }),

        username: z
            .string()
            .min(1, {
                message: "Username kiritilishi shart.",
            })
            .max(30, {
                message: "Username 30 ta belgidan oshmasligi kerak.",
            }),

        password: z
            .string()
            .refine(
                (value) => value === "" || value.length >= 8,
                { message: "Parol kamida 8 ta belgidan iborat bo‘lishi kerak." },
            )
            .max(255, {
                message: "Parol 255 ta belgidan oshmasligi kerak.",
            }),

        password_confirmation: z.string(),
    })
    .refine(
        (data) =>
            data.password === "" ||
            data.password === data.password_confirmation,
        {
            message: "Parollar bir-biriga mos kelmaydi.",
            path: ["password_confirmation"],
        },
    );

export type StudentForm = z.infer<typeof studentFormSchema>;
type StudentFormData = z.input<typeof studentFormSchema>;

export function useStudentForm(
    initialData?: StudentFormData,
    studentId?: number,
) {
    const form = useForm<StudentFormData>({
        name: initialData?.name ?? "",
        surname: initialData?.surname ?? "",
        email: initialData?.email ?? "",
        username: initialData?.username ?? "",
        password: initialData?.password ?? "",
        password_confirmation: initialData?.password_confirmation ?? "",
    });

    function validate(): boolean {
        form.clearErrors();

        const result = studentFormSchema.safeParse(form.data());

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
        const isValid = validate();

        if (!isValid) {
            return;
        }

        if (studentId !== undefined) {
            form.put(update(studentId).url);
            return;
        }

        form.post(store().url);
    }

    return {
        form,
        validate,
        submit,
    };
}

export type StudentFormInstance = ReturnType<typeof useStudentForm>["form"];