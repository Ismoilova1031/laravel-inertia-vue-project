<template>
    <EmptyState
        v-if="students.length === 0"
        icon="mdi-account-group-outline"
        title="No students yet"
        text="Add the first student."
    />

    <v-card v-else rounded="lg" elevation="1">
        <v-list lines="two" class="py-0">
            <template v-for="(student, index) in students" :key="student.id">
                <v-divider v-if="index > 0" />
                <v-list-item class="px-5 py-3">
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
                    <v-list-item-subtitle class="mt-1">{{
                        student.email
                    }}</v-list-item-subtitle>

                    <template #append>
                        <v-chip
                            size="small"
                            color="primary"
                            variant="tonal"
                            class="mr-2"
                            >@{{ student.username }}</v-chip
                        >

                        <v-menu>
                            <template #activator="{ props: menu }">
                                <v-btn
                                    v-bind="menu"
                                    icon="mdi-dots-vertical"
                                    variant="text"
                                    size="small"
                                    aria-label="Student actions"
                                />
                            </template>

                            <v-list density="compact">
                                <v-list-item
                                    prepend-icon="mdi-school-outline"
                                    title="View as student"
                                    @click="
                                        router.visit(
                                            LearningController.courses({
                                                student: student.id,
                                            }).url,
                                        )
                                    "
                                />
                                <v-list-item
                                    prepend-icon="mdi-pencil-outline"
                                    title="Edit"
                                    @click="
                                        router.visit(
                                            StudentController.edit(student.id)
                                                .url,
                                        )
                                    "
                                />
                                <v-list-item
                                    prepend-icon="mdi-delete-outline"
                                    title="Delete"
                                    class="text-error"
                                    @click="ask(student)"
                                />
                            </v-list>
                        </v-menu>
                    </template>
                </v-list-item>
            </template>
        </v-list>
    </v-card>

    <ConfirmDialog
        v-model="open"
        title="Delete student"
        confirm-label="Delete"
        color="error"
        :loading="deleting"
        @confirm="remove"
    >
        <strong>{{ selected?.name }} {{ selected?.surname }}</strong> will be
        deleted together with enrollments and results. This cannot be undone.
    </ConfirmDialog>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import LearningController from "@/actions/App/Http/Controllers/LearningController";
import StudentController from "@/actions/App/Http/Controllers/StudentController";
import ConfirmDialog from "@/components/Common/ConfirmDialog.vue";
import EmptyState from "@/components/Common/EmptyState.vue";
import type { Student } from "@/types/student";

defineProps<{ students: Student[] }>();

const open = ref(false);
const deleting = ref(false);
const selected = ref<Student | null>(null);

function ask(student: Student) {
    selected.value = student;
    open.value = true;
}

function remove() {
    if (!selected.value || deleting.value) return;

    router.delete(StudentController.destroy(selected.value.id).url, {
        preserveScroll: true,
        onStart: () => (deleting.value = true),
        onSuccess: () => (open.value = false),
        onFinish: () => (deleting.value = false),
    });
}
</script>
