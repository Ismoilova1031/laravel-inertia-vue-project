<template>
    <EmptyState
        v-if="students.length === 0"
        icon="mdi-account-group-outline"
        title="No students yet"
        text="Add a new student or enroll an existing one."
    />

    <v-card v-else rounded="lg" elevation="1">
        <v-list class="py-0">
            <template v-for="(student, index) in students" :key="student.id">
                <v-divider v-if="index > 0" />
                <v-list-item
                    class="px-5 py-4"
                    :class="{ inactive: !student.isActive }"
                >
                    <template #prepend>
                        <v-avatar
                            color="primary"
                            variant="tonal"
                            size="42"
                            class="mr-3"
                            >{{ index + 1 }}</v-avatar
                        >
                    </template>

                    <v-list-item-title class="font-weight-medium">
                        {{ student.name }} {{ student.surname }}
                    </v-list-item-title>
                    <v-list-item-subtitle class="mt-1"
                        >{{ student.email }} · @{{
                            student.username
                        }}</v-list-item-subtitle
                    >

                    <template #append>
                        <v-chip
                            size="small"
                            class="mr-2"
                            :color="student.isActive ? 'success' : 'error'"
                            :variant="student.isActive ? 'tonal' : 'outlined'"
                        >
                            {{ student.isActive ? "Active" : "Inactive" }}
                        </v-chip>
                        <v-btn
                            v-if="student.isActive"
                            icon="mdi-account-remove-outline"
                            color="error"
                            variant="text"
                            size="small"
                            aria-label="Remove from course"
                            @click="ask('remove', student)"
                        />
                        <v-btn
                            v-else
                            icon="mdi-account-check-outline"
                            color="success"
                            variant="text"
                            size="small"
                            aria-label="Re-enroll"
                            @click="ask('activate', student)"
                        />
                    </template>
                </v-list-item>
            </template>
        </v-list>
    </v-card>

    <div class="d-flex ga-4 mt-4">
        <AddCard
            class="actions"
            label="Add New Student"
            :disabled="!isPublished"
            @click="
                router.visit(StudentController.createForCourse(courseId).url)
            "
        />
        <AddCard
            class="actions"
            label="Enroll Existing Student"
            icon="mdi-account-plus-outline"
            :disabled="!isPublished"
            @click="enrollOpen = true"
        />
    </div>

    <ConfirmDialog
        v-model="removeOpen"
        title="Remove from course"
        confirm-label="Remove"
        color="error"
        :loading="processing"
        @confirm="removeSelected"
    >
        <strong>{{ selected?.name }} {{ selected?.surname }}</strong> will lose
        access. Results are kept and the student can be re-enrolled later.
    </ConfirmDialog>

    <ConfirmDialog
        v-model="activateOpen"
        title="Re-enroll student"
        confirm-label="Re-enroll"
        color="success"
        :loading="processing"
        @confirm="activateSelected"
    >
        <strong>{{ selected?.name }} {{ selected?.surname }}</strong> will get
        access to the course again.
    </ConfirmDialog>

    <EnrollStudentDialog
        v-model="enrollOpen"
        :students="availableStudents"
        :loading="processing"
        @enroll="enrollSelected"
    />

    <v-snackbar
        v-model="snackbar.show"
        :color="snackbar.color"
        location="top center"
        timeout="4000"
    >
        {{ snackbar.text }}
    </v-snackbar>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import StudentController from "@/actions/App/Http/Controllers/StudentController";
import AddCard from "@/components/Common/AddCard.vue";
import ConfirmDialog from "@/components/Common/ConfirmDialog.vue";
import EmptyState from "@/components/Common/EmptyState.vue";
import EnrollStudentDialog from "@/components/Student/EnrollStudentDialog.vue";
import { useEnrollmentActions } from "@/composables/useEnrollmentActions";
import { useSnackbar } from "@/composables/useSnackbar";
import type { Student, StudentForCourse } from "@/types/student";

const props = defineProps<{
    students: StudentForCourse[];
    availableStudents: Student[];
    courseId: number;
    isPublished: boolean;
}>();

const { snackbar, notify } = useSnackbar();
const { processing, enroll, remove } = useEnrollmentActions(
    () => props.courseId,
    notify,
);

const selected = ref<StudentForCourse | null>(null);
const removeOpen = ref(false);
const activateOpen = ref(false);
const enrollOpen = ref(false);

function ask(kind: "remove" | "activate", student: StudentForCourse) {
    selected.value = student;
    (kind === "remove" ? removeOpen : activateOpen).value = true;
}

const removeSelected = () =>
    selected.value &&
    remove(selected.value.id, () => (removeOpen.value = false));

const activateSelected = () =>
    selected.value &&
    enroll(selected.value.id, () => (activateOpen.value = false));

const enrollSelected = (studentId: number) =>
    enroll(studentId, () => (enrollOpen.value = false));
</script>

<style scoped>
.inactive {
    opacity: 0.45;
}

.actions {
    flex: 1 1 0;
    min-width: 0;
}
</style>
