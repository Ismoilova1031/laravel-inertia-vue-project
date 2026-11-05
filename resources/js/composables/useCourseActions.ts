import { router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import CourseController from "@/actions/App/Http/Controllers/CourseController";
import type { Notify } from "@/composables/useSnackbar";
import type { Course } from "@/types/course";
import { courseActionMeta } from "@/utils/courseStatus";
import { firstError } from "@/utils/inertia";

export function useCourseActions(notify: Notify) {
    const dialog = ref(false);
    const processing = ref(false);
    const selected = ref<Course | null>(null);

    const meta = computed(() =>
        selected.value ? courseActionMeta[selected.value.status.value] : null,
    );

    function open(course: Course) {
        selected.value = course;
        dialog.value = true;
    }

    function close() {
        dialog.value = false;
    }

    function confirm() {
        const course = selected.value;
        const current = meta.value;
        if (!course || !current || processing.value) return;

        processing.value = true;

        const options = {
            preserveScroll: true,
            onSuccess: () => notify(`${current.confirm}: done`),
            onError: (errors: Record<string, string>) =>
                notify(firstError(errors), "error"),
            onFinish: () => {
                processing.value = false;
                close();
            },
        };

        if (current.target === null) {
            router.delete(CourseController.destroy(course.id).url, options);
        } else {
            router.patch(
                CourseController.changeStatus(course.id).url,
                { status: current.target },
                options,
            );
        }
    }

    return { dialog, processing, selected, meta, open, close, confirm };
}
