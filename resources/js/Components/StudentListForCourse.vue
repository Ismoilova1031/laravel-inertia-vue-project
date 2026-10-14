<template>
    <v-card rounded="lg" elevation="1">
        <v-list lines="two" class="py-0">
            <v-list-item v-for="(student, index) in students" :key="student.id" class="px-5 py-3">
                <template #prepend>
                    <v-avatar color="primary" variant="tonal" size="42">
                        {{ index + 1 }}
                    </v-avatar>
                </template>

                <v-list-item-title class="font-weight-medium">
                    {{ student.name }} {{ student.surname }}
                </v-list-item-title>

                <v-list-item-subtitle class="mt-1">
                    {{ student.email }}
                </v-list-item-subtitle>

                <template #append>
                    <div class="d-flex align-center ga-3">
                        <v-chip size="small" color="primary" variant="tonal">
                            @{{ student.username }}
                        </v-chip>

                        <v-list-item prepend-icon="mdi-account-remove-outline" title="Kursdan chiqarish"
                            class="text-error" @click="confirmRemove(student)" />
                    </div>
                </template>
            </v-list-item>

            <v-list-item v-if="students.length === 0" class="text-center py-10">
                <v-icon size="40" color="medium-emphasis">
                    mdi-account-group-outline
                </v-icon>

                <v-list-item-title>
                    Hozircha studentlar yo‘q
                </v-list-item-title>

                <v-list-item-subtitle class="text-body-2">
                    Birinchi studentni qo‘shing.
                </v-list-item-subtitle>
            </v-list-item>
        </v-list>
    </v-card>
    <v-dialog v-model="removeDialog" max-width="420">
        <v-card rounded="lg">
            <v-card-title class="pa-5">
                Studentni kursdan chiqarish
            </v-card-title>

            <v-card-text>
                <strong>
                    {{ selectedStudent?.name }}
                    {{ selectedStudent?.surname }}
                </strong>
                studentini kursdan chiqarishni xohlaysizmi?
            </v-card-text>

            <v-card-actions class="pa-5 justify-end">
                <v-btn variant="text" :disabled="removing" @click="removeDialog = false">
                    Bekor qilish
                </v-btn>

                <v-btn color="error" variant="flat" :loading="removing" @click="removeStudent">
                    Chiqarish
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { edit } from '../actions/App/Http/Controllers/StudentController';
import EnrollmentController from '../actions/App/Http/Controllers/EnrollmentController';
import type { Student } from '../types/student';

const props = defineProps<{
    students: Student[];
    availableStudents: Student[];
    courseId: number;
}>();

const removeDialog = ref(false);
const selectedStudent = ref<Student | null>(null);
const removing = ref(false);

function confirmRemove(student: Student) {
    selectedStudent.value = student;
    removeDialog.value = true;
}

function removeStudent() {
    if (!selectedStudent.value || removing.value) return;
    removing.value = true;

    router.delete(
        EnrollmentController.destroy({ course: props.courseId, student: selectedStudent.value.id }).url,
        {
            preserveScroll: true,
            onSuccess: () => {
                removeDialog.value = false;
                selectedStudent.value = null;
            },
            onFinish: () => (removing.value = false),
        },
    );
}

</script>
