import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import EnrollmentController from "@/actions/App/Http/Controllers/EnrollmentController";
import type { Notify } from "@/composables/useSnackbar";
import { firstError } from "@/utils/inertia";

export function useEnrollmentActions(courseId: () => number, notify: Notify) {
    const processing = ref(false);

    const options = (onDone: () => void) => ({
        preserveScroll: true,
        onStart: () => (processing.value = true),
        onSuccess: onDone,
        onError: (errors: Record<string, string>) => notify(firstError(errors), "error"),
        onFinish: () => (processing.value = false),
    });

    function enroll(studentId: number, onDone: () => void) {
        if (processing.value) return;

        router.post(EnrollmentController.store(courseId()).url, { student_id: studentId }, options(onDone));
    }

    function remove(studentId: number, onDone: () => void) {
        if (processing.value) return;

        router.delete(
            EnrollmentController.destroy({ course: courseId(), student: studentId }).url,
            options(onDone),
        );
    }

    return { processing, enroll, remove };
}