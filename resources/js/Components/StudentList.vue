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

                        <v-menu>
                            <template #activator="{ props }">
                                <v-btn v-bind="props" icon="mdi-dots-vertical" variant="text" size="small"
                                    aria-label="Student amallari" />
                            </template>

                            <v-list density="compact">
                                <v-list-item prepend-icon="mdi-pencil-outline" title="Tahrirlash"
                                    @click="router.visit(edit(student.id).url)" />
                                <v-list-item prepend-icon="mdi-account-remove-outline" title="Kursdan chiqarish"
                                    class="text-error" @click="confirmRemove(student)" />
                            </v-list>
                        </v-menu>
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
    <div class="d-flex ga-4 mt-4" v-if="isPublished">
        <Link :href="`${StudentController.create().url}?course=${courseId}`" class="add-link">
            <v-card link rounded="lg" class="border border-dashed bg-transparent h-100" elevation="0">
                <div class="d-flex align-center justify-center ga-3 px-5 py-4">
                    <v-icon icon="mdi-plus" />
                    <span class="font-weight-medium">Add New Student</span>
                </div>
            </v-card>
        </Link>

        <v-card link rounded="lg" class="add-link border border-dashed bg-transparent" elevation="0"
            @click="enrollDialog = true">
            <div class="d-flex align-center justify-center ga-3 px-5 py-4">
                <v-icon icon="mdi-check" />
                <span class="font-weight-medium">Enroll Student in Course</span>
            </div>
        </v-card>
    </div>
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

    <v-dialog v-model="enrollDialog" max-width="480">
        <v-card rounded="lg">
            <v-card-title class="pa-5">Studentni kursga yozish</v-card-title>
            <v-card-text>
                <v-autocomplete v-model="enrollForm.student_id" :items="availableStudents" item-value="id"
                    :item-title="(s: Student) => `${s.name} ${s.surname} (@${s.username})`" label="Student"
                    :error-messages="enrollForm.errors.student_id" />
            </v-card-text>
            <v-card-actions class="pa-5 justify-end">
                <v-btn variant="text" @click="enrollDialog = false">Bekor qilish</v-btn>
                <v-btn color="primary" variant="flat" :loading="enrollForm.processing"
                    :disabled="!enrollForm.student_id" @click="enroll">Yozish</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import StudentController, { edit } from '../actions/App/Http/Controllers/StudentController';
import EnrollmentController from '../actions/App/Http/Controllers/EnrollmentController';
import type { Student } from '../types/student';

const props = defineProps<{
    students: Student[];
    availableStudents: Student[];
    courseId: number;
    isPublished: boolean;
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

const enrollDialog = ref(false);
const enrollForm = useForm({ student_id: null as number | null });

function enroll() {
    enrollForm.post(EnrollmentController.store(props.courseId).url, {
        preserveScroll: true,
        onSuccess: () => {
            enrollDialog.value = false;
            enrollForm.reset();
        },
    });
}
</script>
<style lang="css" scoped>
.items {
    width: 80%;
}

.add-link {
    flex: 1 1 0;
    min-width: 0;
    display: block;
    text-decoration: none;
    color: inherit;
}
</style>
