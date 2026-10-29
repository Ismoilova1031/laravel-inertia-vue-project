<!-- resources/js/pages/Learn/Course.vue -->
<template>
    <v-container class="py-8">
        <StudentViewBanner :student="student" />

        <v-btn variant="text" prepend-icon="mdi-arrow-left" class="mb-4 px-0"
            @click="router.visit(LearningController.courses({ student: student.id }).url)">
            My courses
        </v-btn>

        <v-card rounded="lg" class="border mb-6" elevation="0">
            <v-card-text class="d-flex align-center ga-6 pa-6 flex-wrap">
                <v-progress-circular :model-value="course.progress.percent"
                    :color="course.progress.percent === 100 ? 'success' : 'primary'" size="96" width="8">
                    <span class="text-h6 font-weight-bold">{{ course.progress.percent }}%</span>
                </v-progress-circular>

                <div class="flex-grow-1">
                    <v-chip size="small" color="primary" variant="tonal" class="mb-2">
                        {{ course.category }}
                    </v-chip>
                    <h1 class="text-h5 font-weight-bold">{{ course.title }}</h1>
                    <p class="text-body-1 text-medium-emphasis mt-1 mb-0">
                        {{ course.description }}
                    </p>
                </div>

                <v-btn v-if="course.isActive && course.nextLessonId" color="primary" size="large"
                    prepend-icon="mdi-play" @click="openLesson(course.nextLessonId)">
                    {{ course.progress.completed === 0 ? "Start course" : "Continue" }}
                </v-btn>
            </v-card-text>
        </v-card>

        <v-alert v-if="!course.isActive" type="warning" variant="tonal" class="mb-6" icon="mdi-lock-outline">
            Your access to this course was removed. Your results are kept, but lessons are locked.
        </v-alert>

        <v-card rounded="lg" class="border" elevation="0">
            <v-list class="py-0" lines="two">
                <template v-for="(lesson, index) in course.lessons" :key="lesson.id">
                    <v-divider v-if="index > 0" />
                    <v-list-item class="px-5 py-3" :disabled="!course.isActive" @click="openLesson(lesson.id)">
                        <template #prepend>
                            <LessonTypeAvatar :type="lesson.type.value" class="mr-4" />
                        </template>

                        <v-list-item-title class="font-weight-medium">
                            {{ index + 1 }}. {{ lesson.title }}
                        </v-list-item-title>
                        <v-list-item-subtitle class="mt-1">
                            {{ lesson.type.label }} · {{ lesson.description }}
                        </v-list-item-subtitle>

                        <template #append>
                            <v-icon :icon="lesson.completed ? 'mdi-check-circle' : 'mdi-circle-outline'"
                                :color="lesson.completed ? 'success' : 'medium-emphasis'" />
                        </template>
                    </v-list-item>
                </template>

                <v-list-item v-if="course.lessons.length === 0" class="text-center py-10">
                    <v-list-item-title>This course has no lessons yet.</v-list-item-title>
                </v-list-item>
            </v-list>
            <CourseCompletionPanel v-if="course.isComplete" :student-id="student.id" :course="course" />
        </v-card>
    </v-container>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import LessonTypeAvatar from "../../components/LessonTypeAvatar.vue";
import StudentViewBanner from "../../components/StudentViewBanner.vue";
import LearningController from "../../actions/App/Http/Controllers/LearningController";
import CourseCompletionPanel from "../../components/CourseCompletionPanel.vue";
import type { LearnCourse } from "../../types/learning";
import type { StudentSummary } from "../../types/student";
const props = defineProps<{
    student: StudentSummary;
    course: LearnCourse;
}>();

function openLesson(lessonId: number) {
    router.visit(
        LearningController.lesson({
            student: props.student.id,
            course: props.course.id,
            lesson: lessonId,
        }).url,
    );
}
</script>