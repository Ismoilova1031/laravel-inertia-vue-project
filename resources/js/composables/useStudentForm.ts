import z from "zod";
import { store, storeForCourse, update } from "@/actions/App/Http/Controllers/StudentController";
import { useZodForm } from "./useZodForm";

export const studentFormSchema = z
    .object({
        name: z
            .string()
            .min(1, { message: "Ism kiritilishi shart." })
            .max(255, { message: "Ism 255 ta belgidan oshmasligi kerak." }),

        surname: z
            .string()
            .min(1, { message: "Familiya kiritilishi shart." })
            .max(255, { message: "Familiya 255 ta belgidan oshmasligi kerak." }),

        email: z
            .string()
            .min(1, { message: "Email kiritilishi shart." })
            .email({ message: "Email manzili noto‘g‘ri formatda." })
            .max(255, { message: "Email 255 ta belgidan oshmasligi kerak." }),

        username: z
            .string()
            .min(1, { message: "Username kiritilishi shart." })
            .max(30, { message: "Username 30 ta belgidan oshmasligi kerak." }),

        password: z
            .string()
            .refine((value) => value === "" || value.length >= 8, {
                message: "Parol kamida 8 ta belgidan iborat bo‘lishi kerak.",
            })
            .max(255, { message: "Parol 255 ta belgidan oshmasligi kerak." }),

        password_confirmation: z.string(),
    })
    .refine((data) => data.password === "" || data.password === data.password_confirmation, {
        message: "Parollar bir-biriga mos kelmaydi.",
        path: ["password_confirmation"],
    });

export type StudentFormData = z.input<typeof studentFormSchema>;

export function useStudentForm(course_id?: number, initialData?: StudentFormData, studentId?: number) {
    const { form, validate } = useZodForm(studentFormSchema, {
        name: initialData?.name ?? "",
        surname: initialData?.surname ?? "",
        email: initialData?.email ?? "",
        username: initialData?.username ?? "",
        password: initialData?.password ?? "",
        password_confirmation: initialData?.password_confirmation ?? "",
    });

    function submit() {
        if (!validate()) return;

        if (studentId !== undefined) {
            form.put(update(studentId).url);
        } else if (course_id !== undefined) {
            form.post(storeForCourse(course_id).url);
        } else {
            form.post(store().url);
        }
    }

    return { form, validate, submit };
}

export type StudentFormInstance = ReturnType<typeof useStudentForm>["form"];