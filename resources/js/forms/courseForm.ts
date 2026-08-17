import z from 'zod';
import { useForm } from '@inertiajs/vue3';

export const courseFormSchema = z.object({
    'title': z.string().min(1, { message: 'Title is required' }).max(255, { message: 'Title must be less than 255 characters' }),
    'description': z.string().min(1, { message: 'Description is required' }).max(255, { message: 'Description must be less than 255 characters' }),
    'category': z.number().min(0).max(9, { message: 'Category is required' }),
    'status': z.number().min(0).max(2, { message: 'Status is required' }),
});

export type CourseForm = z.infer<typeof courseFormSchema>;

export function useCourseForm(InitialData?: CourseForm, courseId?: number) {
    const form = useForm<CourseForm>({
        title: InitialData?.title ?? '',
        description: InitialData?.description ?? '',
        category: InitialData?.category ?? 10, // None
        status: InitialData?.status ?? 3, // None
    });
    
    function validate(): boolean {
        form.clearErrors();

        const result = courseFormSchema.safeParse(form.data());

        if(!result.success){
            result.error.issues.forEach((issue) => {
                const fieldName = issue.path[0] as keyof typeof form.data;
                form.setError(fieldName, issue.message);
            })

            return false;
        }

        return true;
    }

    function submit(): void {
        if(!validate()){
            return;
        }

        if(courseId){
            form.put(`/courses/${courseId}`, {
                onSuccess: () => {
                    form.reset();
                }
            });
            return;
        }

        form.post('/courses', {
            onSuccess: () => {
                form.reset();
            }
        });
    }

    return {
        form,
        submit,
        validate,
    }
}
