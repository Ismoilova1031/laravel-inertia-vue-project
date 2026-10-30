<!-- resources/js/pages/Learn/Lesson.vue -->
<template>
    <v-container class="py-8 lesson-page">
        <StudentViewBanner :student="student" />

        <v-breadcrumbs :items="breadcrumbs" class="px-0 pt-0" />

        <v-card rounded="lg" class="border" elevation="0">
            <v-card-text class="d-flex align-center ga-4 pa-6">
                <LessonTypeAvatar :type="lesson.type.value" :size="48" />
                <div class="flex-grow-1">
                    <h1 class="text-h5 font-weight-bold">{{ lesson.title }}</h1>
                    <p class="text-body-2 text-medium-emphasis mb-0">{{ lesson.description }}</p>
                </div>
                <v-chip v-if="lesson.completed" color="success" variant="tonal" prepend-icon="mdi-check">
                    Completed
                </v-chip>
            </v-card-text>

            <v-divider />

            <v-card-text class="pa-6">
                <video v-if="isVideo && lesson.videoUrl" :src="lesson.videoUrl" controls class="w-100 rounded-lg" />
                <v-alert v-else-if="isVideo" type="info" variant="tonal">
                    The video for this lesson is not available yet.
                </v-alert>

                <div v-else-if="isText" class="lesson-content" v-html="lesson.content" />

                <TaskPanel v-else-if="lesson.task" :student-id="student.id" :task="lesson.task" />

                <v-alert v-else type="info" variant="tonal" icon="mdi-clipboard-check-outline">
                    This lesson has no task yet.
                </v-alert>
            </v-card-text>

            <v-divider />

            <v-card-actions class="pa-4 ga-2">
                <v-btn v-if="lesson.previous" variant="text" prepend-icon="mdi-arrow-left"
                    @click="goTo(lesson.previous.id)">
                    Previous
                </v-btn>

                <v-spacer />

                <v-btn v-if="!isTask" :color="lesson.completed ? 'success' : 'primary'"
                    :variant="lesson.completed ? 'tonal' : 'flat'"
                    :prepend-icon="lesson.completed ? 'mdi-check' : 'mdi-check-circle-outline'"
                    :disabled="lesson.completed" :loading="completing" @click="markCompleted">
                    {{ lesson.completed ? "Completed" : "Mark as completed" }}
                </v-btn>

                <v-btn v-if="lesson.next" variant="outlined" append-icon="mdi-arrow-right"
                    @click="goTo(lesson.next.id)">
                    Next
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import LessonTypeAvatar from "../../components/Lesson/LessonTypeAvatar.vue";
import StudentViewBanner from "../../components/Student/StudentViewBanner.vue";
import LearningController from "../../actions/App/Http/Controllers/LearningController";
import { LessonType } from "../../types/lesson";
import type { LearnLesson } from "../../types/learning";
import TaskPanel from "../../components/Learn/TaskPanel.vue";
import type { StudentSummary } from "../../types/student";

const props = defineProps<{
    student: StudentSummary;
    lesson: LearnLesson;
}>();

const completing = ref(false);

const isVideo = computed(() => props.lesson.type.value === LessonType.VIDEO);
const isText = computed(() => props.lesson.type.value === LessonType.TEXT);
const isTask = computed(() => props.lesson.type.value === LessonType.TASK);

const courseUrl = computed(
    () =>
        LearningController.course({
            student: props.student.id,
            course: props.lesson.course.id,
        }).url,
);

const breadcrumbs = computed(() => [
    {
        title: "My courses",
        href: LearningController.courses({ student: props.student.id }).url,
    },
    { title: props.lesson.course.title, href: courseUrl.value },
    { title: props.lesson.title, disabled: true },
]);

function goTo(lessonId: number) {
    router.visit(
        LearningController.lesson({
            student: props.student.id,
            course: props.lesson.course.id,
            lesson: lessonId,
        }).url,
    );
}

function markCompleted() {
    router.post(
        LearningController.complete({ student: props.student.id, lesson: props.lesson.id }).url,
        {},
        {
            preserveScroll: true,
            onStart: () => (completing.value = true),
            onFinish: () => (completing.value = false),
        },
    );
}
</script>

<style scoped>
.lesson-page {
    max-width: 960px;
}

.lesson-content :deep(img),
.lesson-content :deep(video) {
    max-width: 100%;
}
</style>