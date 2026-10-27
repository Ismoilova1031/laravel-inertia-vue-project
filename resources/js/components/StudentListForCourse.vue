<template>
    <v-card rounded="lg" elevation="1">
        <v-list class="py-0">
            <v-list-item v-for="(student, index) in students" :key="student.id" class="px-5 py-4 student-item"
                :class="{ 'inactive-student': !student.isActive }">
                <template #prepend>
                    <v-avatar color="primary" variant="tonal" size="42" class="mr-3">
                        {{ index + 1 }}
                    </v-avatar>
                </template>
                <template #default>
                    <v-row align="center">
                        <v-col cols="12" sm="5">
                            <div class="font-weight-medium text-body-1">
                                {{ student.name }} {{ student.surname }}
                            </div>

                            <div class="text-body-2 text-medium-emphasis mt-1">
                                {{ student.email }}
                            </div>
                        </v-col>

                        <v-col cols="6" sm="3" class="mt-3 mt-sm-0">
                            <span class="text-body-2 text-primary font-weight-medium">
                                @{{ student.username }}
                            </span>
                        </v-col>

                        <v-col cols="4" sm="2" class="mt-3 mt-sm-0">
                            <v-chip size="small" :color="student.isActive ? 'success' : 'error'" :variant="student.isActive ? 'tonal' : 'outlined'
                                ">
                                {{ student.isActive ? "Active" : "Inactive" }}
                            </v-chip>
                        </v-col>

                        <v-col cols="2" sm="2" class="d-flex justify-end mt-3 mt-sm-0">
                            <v-btn v-if="!student.isActive" :icon="'mdi-account-check-outline'" :color="student.isActive ? 'warning' : 'success'
                                " variant="text" size="small" @click="confirmActivate(student)" />

                            <v-btn v-else icon="mdi-account-remove-outline" color="error" variant="text" size="small"
                                @click="confirmRemove(student)" />
                        </v-col>
                    </v-row>
                </template>
            </v-list-item>

            <v-divider v-if="students.length > 0" />

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
    <div class="d-flex ga-4 mt-4">
        <v-card link rounded="lg" class="add-link border border-dashed bg-transparent h-100" elevation="0"
            :disabled="!isPublished" @click="
                router.visit(StudentController.createForCourse(courseId).url)
                ">
            <div class="d-flex align-center justify-center ga-3 px-5 py-4">
                <v-icon icon="mdi-plus" />
                <span class="font-weight-medium">Add New Student</span>
            </div>
        </v-card>

        <v-card link rounded="lg" class="add-link border border-dashed bg-transparent" elevation="0"
            @click="enrollDialog = true" :disabled="!isPublished">
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

    <v-dialog v-model="activateDialog" max-width="420">
        <v-card rounded="lg">
            <v-card-title class="pa-5">
                Studentni kursga qo'shish
            </v-card-title>

            <v-card-text>
                <strong>
                    {{ selectedStudent?.name }}
                    {{ selectedStudent?.surname }}
                </strong>
                studentini kursga qo'shishni xohlaysizmi?
            </v-card-text>

            <v-card-actions class="pa-5 justify-end">
                <v-btn variant="text" :disabled="enrollForm.processing" @click="activateDialog = false">
                    Bekor qilish
                </v-btn>

                <v-btn color="success" variant="flat" :loading="enrollForm.processing" @click="activateStudent">
                    Qo'shish
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-dialog v-model="enrollDialog" max-width="480">
        <v-card rounded="lg">
            <v-card-title class="pa-5">Studentni kursga yozish</v-card-title>
            <v-card-text>
                <v-autocomplete v-model="enrollForm.student_id" :items="availableStudents" item-value="id" :item-title="(s: Student) =>
                        `${s.name} ${s.surname} (@${s.username})`
                    " label="Student" :error-messages="enrollForm.errors.student_id" />
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
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import EnrollmentController from "../actions/App/Http/Controllers/EnrollmentController";
import type { StudentForCourse } from "../types/student";
import type { Student } from "../types/student";
import { useForm } from "@inertiajs/vue3";
import StudentController from "../actions/App/Http/Controllers/StudentController";
const props = defineProps<{
    students: StudentForCourse[];
    availableStudents: Student[];
    courseId: number;
    isPublished: boolean;
}>();

const removeDialog = ref(false);
const removing = ref(false);
const selectedStudent = ref<StudentForCourse | null>(null);

const enrollDialog = ref(false);
const activateDialog = ref(false);
const enrollForm = useForm({ student_id: null as number | null });

function confirmRemove(student: StudentForCourse) {
    selectedStudent.value = student;
    removeDialog.value = true;
}

function confirmActivate(student: StudentForCourse) {
    selectedStudent.value = student;
    activateDialog.value = true;
}

function removeStudent() {
    if (!selectedStudent.value || removing.value) return;
    removing.value = true;

    router.delete(
        EnrollmentController.destroy({
            course: props.courseId,
            student: selectedStudent.value.id,
        }).url,
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

function enrollStudent(studentId: number | null, onDone: () => void) {
    if (studentId === null || enrollForm.processing) return;

    enrollForm.student_id = studentId;
    enrollForm.post(EnrollmentController.store(props.courseId).url, {
        preserveScroll: true,
        onSuccess: () => {
            onDone();
            enrollForm.reset();
        },
    });
}

function enroll() {
    enrollStudent(enrollForm.student_id, () => (enrollDialog.value = false));
}

function activateStudent() {
    enrollStudent(selectedStudent.value?.id ?? null, () => {
        activateDialog.value = false;
        selectedStudent.value = null;
    });
}
</script>
<style scoped>
.inactive-student {
    opacity: 0.4;
}

.add-link {
    flex: 1 1 0;
    min-width: 0;
    display: block;
    text-decoration: none;
    color: inherit;
}
</style>
