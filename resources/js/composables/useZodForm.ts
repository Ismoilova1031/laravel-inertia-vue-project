import { useForm, type InertiaForm } from "@inertiajs/vue3";
import type { z } from "zod";

export function useZodForm<S extends z.ZodTypeAny>(schema: S, initial: z.input<S>) {
    type Data = z.input<S> & Record<string, unknown>;

    const form = useForm(initial as Record<string, never>) as unknown as InertiaForm<any>;

    function validate(): boolean {
        form.clearErrors();

        const result = schema.safeParse(form.data());
        if (result.success) return true;

        const errors: Record<string, string> = {};
        for (const issue of result.error.issues) {
            const path = issue.path.join(".");
            if (path && !errors[path]) errors[path] = issue.message;
        }

        form.setError(errors);
        return false;
    }

    return { form, validate };
}