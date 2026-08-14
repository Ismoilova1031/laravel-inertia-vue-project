import z from 'zod';
import { useForm } from '@inertiajs/vue3';

export const courseFormSchema = z.object({
    'title': z.string().min(1, { message: 'Title is required' }).max(255, { message: 'Title must be less than 255 characters' }),
    'description': z.string().min(1, { message: 'Description is required' }).max(255, { message: 'Description must be less than 255 characters' }),
});

export type CourseForm = z.infer<typeof courseFormSchema>;

export function useCourseForm(){
    const form = useForm<CourseForm>({
        title: '',
        description: '',
    });
    
    function validate(): boolean {
        form.clearErrors();

        const result = courseFormSchema.safeParse(form.data);

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
