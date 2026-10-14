
<template>
    <v-card rounded="lg" elevation="1">
        <v-list lines="two" class="py-0">
            <v-list-item
                v-for="(student, index) in students"
                :key="student.id"
                class="px-5 py-3"
            >
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
                        <v-chip
                            size="small"
                            color="primary"
                            variant="tonal"
                        >
                            @{{ student.username }}
                        </v-chip>

                        <v-menu>
                            <template #activator="{ props }">
                                <v-btn
                                    v-bind="props"
                                    icon="mdi-dots-vertical"
                                    variant="text"
                                    size="small"
                                    aria-label="Student amallari"
                                />
                            </template>

                            <v-list density="compact">
                                <v-list-item
                                    :href="edit(student.id).url"
                                    prepend-icon="mdi-pencil-outline"
                                    title="Tahrirlash"
                                />

                                <v-list-item
                                    prepend-icon="mdi-delete-outline"
                                    title="O‘chirish"
                                    class="text-error"
                                    @click="confirmDelete(student)"
                                />
                            </v-list>
                        </v-menu>
                    </div>
                </template>
            </v-list-item>

            <v-list-item
                v-if="students.length === 0"
                class="text-center py-10"
            >
                <template #prepend>
                    <v-icon size="40" color="medium-emphasis">
                        mdi-account-group-outline
                    </v-icon>
                </template>

                <v-list-item-title>
                    Hozircha studentlar yo‘q
                </v-list-item-title>

                <v-list-item-subtitle class="text-body-2">
                    Birinchi studentni qo‘shing.
                </v-list-item-subtitle>
            </v-list-item>
        </v-list>
    </v-card>

    <v-dialog v-model="deleteDialog" max-width="420">
        <v-card rounded="lg">
            <v-card-title class="pa-5">
                Studentni o‘chirish
            </v-card-title>

            <v-card-text>
                <strong>
                    {{ selectedStudent?.name }}
                    {{ selectedStudent?.surname }}
                </strong>
                studentini o‘chirishni xohlaysizmi? Bu amalni ortga qaytarib bo‘lmasligi mumkin.
            </v-card-text>

            <v-card-actions class="pa-5 justify-end">
                <v-btn
                    variant="text"
                    :disabled="deleting"
                    @click="deleteDialog = false"
                >
                    Bekor qilish
                </v-btn>

                <v-btn
                    color="error"
                    variant="flat"
                    :loading="deleting"
                    @click="deleteStudent"
                >
                    O‘chirish
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import {
    edit,
    destroy,
} from '../actions/App/Http/Controllers/StudentController';
import type { Student } from '../types/student';

defineProps<{
    students: Student[];
}>();

const deleteDialog = ref(false);
const selectedStudent = ref<Student | null>(null);
const deleting = ref(false);

function confirmDelete(student: Student) {
    selectedStudent.value = student;
    deleteDialog.value = true;
}

function deleteStudent() {
    if (!selectedStudent.value || deleting.value) {
        return;
    }

    deleting.value = true;

    router.delete(destroy(selectedStudent.value.id).url, {
        onSuccess: () => {
            deleteDialog.value = false;
            selectedStudent.value = null;
        },
        onFinish: () => {
            deleting.value = false;
        },
    });
}
</script>