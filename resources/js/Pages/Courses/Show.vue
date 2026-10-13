<template>
    <v-container class="py-8">
        <v-card class="mx-auto border" rounded="lg" elevation="0">
            <v-card-text class="d-flex justify-space-between align-center ga-4 pa-6">
                <div class="d-flex flex-column">
                    <v-card-title class="text-title-large font-weight-bold pa-0">
                        {{ course.title }}
                    </v-card-title>

                    <p class="text-body-1 text-medium-emphasis mb-0">
                        {{ course.description }}
                    </p>
                </div>

                <div class="d-flex ga-2 flex-shrink-0">
                    <v-chip color="primary" variant="tonal">
                        {{ course.category.label }}
                    </v-chip>

                    <v-chip color="success" variant="tonal">
                        {{ course.status.label }}
                    </v-chip>
                </div>
            </v-card-text>

            <v-divider />

            <v-card-item class="d-flex justify-space-between align-center px-6 py-4">
                <div class="informations">
                    <v-icon size="small" class="me-2">mdi-account-multiple</v-icon>
                    <span class="text-body-2 text-medium-emphasis">{{ students }} students enrolled</span>

                    <v-icon size="small" class="ms-4 me-2">mdi-book</v-icon>
                    <span class="text-body-2 text-medium-emphasis">{{ lessons }} lessons</span>
                </div>
            </v-card-item>
        </v-card>

        <v-tabs v-model="tab" class="mt-6" background-color="primary" dark>
            <v-tab value="Lessons">Lessons</v-tab>
            <v-tab value="Students">Students</v-tab>
            <v-tab value="Settings">Settings</v-tab>
        </v-tabs>

        <v-divider />

        <v-tabs-window v-model="tab" class="mt-4">
            <v-tabs-window-item value="Lessons">
                <LessonsList :lessons="course.lessons" :course="course" />
            </v-tabs-window-item>
            <v-tabs-window-item value="Students">
                <StudentList :students="course.students" :available-students="availableStudents"
                    :course-id="course.id" />
                <div class="d-flex ga-4 mt-4">
                    <v-card link rounded="lg" class="add-link border border-dashed bg-transparent h-100" elevation="0"
                        :disabled="!isPublished"
                        @click="router.visit(StudentController.createForCourse(course.id).url)">
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
            </v-tabs-window-item>
            <v-tabs-window-item value="Settings">
                <v-card max-width="900" class="mx-auto" rounded="lg" elevation="2">
                    <v-card-item>
                        <v-card-title class="text-h4 font-weight-bold">Course Settings</v-card-title>

                        <v-card-subtitle class="mt-2">Update the course settings below.</v-card-subtitle>
                    </v-card-item>

                    <v-divider />

                    <v-card-text class="pa-6">
                        <CourseForm :form="form" :categories="categories" :statuses="statuses"
                            submit-label="Update Course" :submit="submit" />
                        <v-snackbar color="success" location="top center" prepend-icon="mdi-check-circle"
                            title="Updated" text="Course updated successfully!" timeout="2000"
                            v-model="form.recentlySuccessful" />
                    </v-card-text>
                </v-card>
            </v-tabs-window-item>
        </v-tabs-window>
    </v-container>
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
import type { CourseDetail } from "../../types/course.ts";
import type { SelectOption } from "../../types/common.ts";
import { ref, computed } from "vue";
import LessonsList from "../../components/LessonsList.vue";
import StudentList from "../../components/StudentList.vue";
import { useCourseForm } from "../../forms/courseForm.js";
import CourseForm from "../../components/CourseForm.vue";
import type { Student } from "../../types/student.ts";
import StudentController from "../../actions/App/Http/Controllers/StudentController.js";
import EnrollmentController from "../../actions/App/Http/Controllers/EnrollmentController.js";
import { router, useForm } from "@inertiajs/vue3";

const { course } = defineProps<{
    course: CourseDetail;
    categories: SelectOption[];
    statuses: SelectOption[];
    availableStudents: Student[];
}>();

const isPublished = computed(() => course.status.value === 1);
const enrollDialog = ref(false);
const enrollForm = useForm({ student_id: null as number | null });

function enroll() {
    enrollForm.post(EnrollmentController.store(course.id).url, {
        preserveScroll: true,
        onSuccess: () => {
            enrollDialog.value = false;
            enrollForm.reset();
        },
    });
}

const lessons = course.lessons.length;
const students = course.students.length;
const tab = ref("Lessons");
const { form, submit } = useCourseForm(
    {
        title: course.title,
        description: course.description,
        category: course.category.value,
        status: course.status.value,
    },
    course.id
);
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