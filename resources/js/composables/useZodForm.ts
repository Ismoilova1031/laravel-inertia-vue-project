import { useForm } from "@inertiajs/vue3";
import type { z } from "zod";

export function useZodForm<S extends z.ZodTypeAny>(schema: S, initial: z.input<S>) {
    const form = useForm<Record<string, any>>(initial as Record<string, any>);

    function validate(): boolean {
        form.clearErrors();

        const result = schema.safeParse(form.data());
        if (result.success) {
            return true;
        }

        const errors: Record<string, string> = {};
        for (const issue of result.error.issues) {
            const path = issue.path.join(".");
            if (path && !errors[path]) {
                errors[path] = issue.message;
            }
        }

        form.setError(errors as any);
        return false;
    }

    return { form, validate };
}