<template>
    <v-container class="py-8">
        <StudentViewBanner :student="student" />

        <div class="mb-6">
            <h1 class="text-h4 font-weight-bold">My courses</h1>
            <p class="text-body-1 text-medium-emphasis mt-1">
                Pick up where you left off.
            </p>
        </div>

        <v-row v-if="courses.length">
            <v-col v-for="course in courses" :key="course.id" cols="12" sm="6" lg="4" xl="3">
                <LearnCourseCard
                    :course="course"
                    @open="openCourse(course)"
                    @continue="openLesson(course)"
                />
            </v-col>
        </v-row>

        <v-card v-else class="pa-8 text-center" variant="outlined" rounded="lg">
            <v-icon icon="mdi-book-open-outline" size="64" class="mb-4" />
            <h2 class="text-h5 mb-2">No courses yet</h2>
            <p class="text-body-1 text-medium-emphasis">
                This student is not enrolled in any course.
            </p>
        </v-card>
    </v-container>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import LearnCourseCard from "../../components/LearnCourseCard.vue";
import StudentViewBanner from "../../components/StudentViewBanner.vue";
import LearningController from "../../actions/App/Http/Controllers/LearningController";
import type { LearnCourseItem } from "../../types/learning";
import type { StudentSummary } from "../../types/student";

const props = defineProps<{
    student: StudentSummary;
    courses: LearnCourseItem[];
}>();

function openCourse(course: LearnCourseItem) {
    router.visit(
        LearningController.course({ student: props.student.id, course: course.id }).url,
    );
}

function openLesson(course: LearnCourseItem) {
    if (course.nextLessonId === null) return;

    router.visit(
        LearningController.lesson({
            student: props.student.id,
            course: course.id,
            lesson: course.nextLessonId,
        }).url,
    );
}
</script>