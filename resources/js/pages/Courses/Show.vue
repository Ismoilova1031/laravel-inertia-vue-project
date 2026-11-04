<template>
    <v-container class="py-8">
        <Head :title="course.title" />

        <CourseHeader :course="course" />

        <v-tabs v-model="tab" class="mt-6">
            <v-tab value="lessons">Lessons</v-tab>
            <v-tab value="students">Students</v-tab>
            <v-tab value="submissions">
                Submissions
                <v-chip
                    v-if="pendingCount"
                    size="x-small"
                    color="warning"
                    variant="tonal"
                    class="ms-2"
                >
                    {{ pendingCount }}
                </v-chip>
            </v-tab>
            <v-tab value="settings">Settings</v-tab>
        </v-tabs>

        <v-divider />

        <v-tabs-window v-model="tab" class="mt-4">
            <v-tabs-window-item value="lessons">
                <LessonsList :lessons="course.lessons" :course="course" />
            </v-tabs-window-item>

            <v-tabs-window-item value="students">
                <StudentListForCourse
                    :students="course.students"
                    :available-students="availableStudents"
                    :course-id="course.id"
                    :is-published="isPublished"
                />
            </v-tabs-window-item>

            <v-tabs-window-item value="submissions">
                <CourseSubmissions :submissions="submissions" />
            </v-tabs-window-item>

            <v-tabs-window-item value="settings">
                <v-card
                    max-width="900"
                    class="mx-auto"
                    rounded="lg"
                    elevation="2"
                >
                    <v-card-item>
                        <v-card-title class="text-h5 font-weight-bold"
                            >Course Settings</v-card-title
                        >
                        <v-card-subtitle class="mt-2"
                            >Update the course settings below.</v-card-subtitle
                        >
                    </v-card-item>

                    <v-divider />

                    <v-card-text class="pa-6">
                        <CourseForm
                            :form="form"
                            :categories="categories"
                            :statuses="statuses"
                            submit-label="Update Course"
                            :submit="submit"
                        />
                    </v-card-text>
                </v-card>
            </v-tabs-window-item>
        </v-tabs-window>

        <v-snackbar
            v-model="form.recentlySuccessful"
            color="success"
            location="top center"
            prepend-icon="mdi-check-circle"
            timeout="2000"
        >
            Course updated successfully!
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import CourseForm from "@/components/Course/CourseForm.vue";
import CourseHeader from "@/components/Course/CourseHeader.vue";
import CourseSubmissions from "@/components/Course/CourseSubmissions.vue";
import LessonsList from "@/components/Lesson/LessonsList.vue";
import StudentListForCourse from "@/components/Student/StudentListForCourse.vue";
import { useCourseForm } from "@/composables/useCourseForm";
import type { SelectOption } from "@/types/common";
import type { CourseDetail } from "@/types/course";
import type { Student } from "@/types/student";
import type { SubmissionRow } from "@/types/submission";
import { COURSE_STATUS } from "@/utils/courseStatus";

const props = defineProps<{
    course: CourseDetail;
    categories: SelectOption[];
    statuses: SelectOption[];
    availableStudents: Student[];
    submissions: SubmissionRow[];
}>();

const tab = ref("lessons");

const isPublished = computed(
    () => props.course.status.value === COURSE_STATUS.PUBLISHED,
);
const pendingCount = computed(
    () => props.submissions.filter((s) => s.status.value === 0).length,
);

const { form, submit } = useCourseForm(
    {
        title: props.course.title,
        description: props.course.description,
        category: props.course.category.value,
        status: props.course.status.value,
    },
    props.course.id,
);
</script>
