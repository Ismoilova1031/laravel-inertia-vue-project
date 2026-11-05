import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import EnrollmentController from "@/actions/App/Http/Controllers/EnrollmentController";

type Notify = (text: string, color?: "success" | "error") => void;

export function useEnrollmentActions(courseId: () => number, notify: Notify) {
    const processing = ref(false);

    const options = (onDone: () => void) => ({
        preserveScroll: true,
        onStart: () => (processing.value = true),
        onSuccess: onDone,
        onError: (errors: Record<string, string>) =>
            notify(Object.values(errors)[0] ?? "Something went wrong", "error"),
        onFinish: () => (processing.value = false),
    });

    function enroll(studentId: number, onDone: () => void) {
        if (processing.value) return;

        router.post(
            EnrollmentController.store(courseId()).url,
            { student_id: studentId },
            options(onDone),
        );
    }

    function remove(studentId: number, onDone: () => void) {
        if (processing.value) return;

        router.delete(
            EnrollmentController.destroy({
                course: courseId(),
                student: studentId,
            }).url,
            options(onDone),
        );
    }

    return { processing, enroll, remove };
}
