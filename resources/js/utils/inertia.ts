export const firstError = (
    errors: Record<string, string>,
    fallback = "Something went wrong",
): string => Object.values(errors)[0] ?? fallback;
