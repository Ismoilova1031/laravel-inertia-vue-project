import z from "zod";
import {
    store,
    storeForCourse,
    update,
} from "@/actions/App/Http/Controllers/StudentController";
import { useZodForm } from "./useZodForm";

const USERNAME = /^[a-zA-Z0-9_]{3,30}$/;

export function buildStudentSchema(requirePassword: boolean) {
    return z
        .object({
            name: z
                .string()
                .min(1, "First name is required")
                .max(255, "First name is too long"),
            surname: z
                .string()
                .min(1, "Last name is required")
                .max(255, "Last name is too long"),
            email: z
                .string()
                .min(1, "Email is required")
                .email("Email format is invalid")
                .max(255, "Email is too long"),
            username: z
                .string()
                .regex(
                    USERNAME,
                    "Username: 3-30 characters, letters, digits and _ only",
                ),
            password: z
                .string()
                .max(255, "Password is too long")
                .refine(
                    (value) =>
                        requirePassword
                            ? value.length >= 8
                            : value === "" || value.length >= 8,
                    {
                        message: "Password must be at least 8 characters",
                    },
                ),
            password_confirmation: z.string(),
        })
        .refine((data) => data.password === data.password_confirmation, {
            message: "Passwords do not match",
            path: ["password_confirmation"],
        });
}

export type StudentFormData = z.input<ReturnType<typeof buildStudentSchema>>;

export function useStudentForm(
    course_id?: number,
    initialData?: StudentFormData,
    studentId?: number,
) {
    const { form, validate } = useZodForm(
        buildStudentSchema(studentId === undefined),
        {
            name: initialData?.name ?? "",
            surname: initialData?.surname ?? "",
            email: initialData?.email ?? "",
            username: initialData?.username ?? "",
            password: initialData?.password ?? "",
            password_confirmation: initialData?.password_confirmation ?? "",
        },
    );

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
