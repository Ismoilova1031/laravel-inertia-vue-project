<template>
    <v-container class="py-8">
        <Head title="My courses" />
        <StudentViewBanner :student="student" />

        <PageHeader title="My courses" subtitle="Pick up where you left off." />

        <v-row v-if="courses.length">
            <v-col
                v-for="course in courses"
                :key="course.id"
                cols="12"
                sm="6"
                lg="4"
                xl="3"
            >
                <LearnCourseCard
                    :course="course"
                    @open="openCourse(course)"
                    @continue="openLesson(course)"
                />
            </v-col>
        </v-row>

        <EmptyState
            v-else
            title="No courses yet"
            text="Not enrolled in any course yet. Browse the catalog to find one."
        >
            <v-btn
                color="primary"
                prepend-icon="mdi-compass-outline"
                @click="
                    router.visit(
                        CatalogController.index({ student: student.id }).url,
                    )
                "
            >
                Browse courses
            </v-btn>
        </EmptyState>
    </v-container>
</template>

<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3";
import CatalogController from "@/actions/App/Http/Controllers/CatalogController";
import LearningController from "@/actions/App/Http/Controllers/LearningController";
import EmptyState from "@/components/Common/EmptyState.vue";
import PageHeader from "@/components/Common/PageHeader.vue";
import LearnCourseCard from "@/components/Learn/LearnCourseCard.vue";
import StudentViewBanner from "@/components/Student/StudentViewBanner.vue";
import type { LearnCourseItem } from "@/types/learning";
import type { StudentSummary } from "@/types/student";

const props = defineProps<{
    student: StudentSummary;
    courses: LearnCourseItem[];
}>();

function openCourse(course: LearnCourseItem) {
    router.visit(
        LearningController.course({
            student: props.student.id,
            course: course.id,
        }).url,
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
