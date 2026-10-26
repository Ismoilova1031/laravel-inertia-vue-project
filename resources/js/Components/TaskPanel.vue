<template>
    <div>
        <div class="d-flex align-center flex-wrap ga-3 mb-4">
            <v-chip color="orange" variant="tonal" prepend-icon="mdi-clipboard-check-outline">
                {{ task.type.label }}
            </v-chip>
            <v-chip variant="tonal">Attempts left: {{ task.attemptsLeft }}</v-chip>
            <v-chip v-if="task.bestScore !== null" color="primary" variant="tonal">
                Best score: {{ task.bestScore }} / {{ task.maxScore }}
            </v-chip>
        </div>

        <v-alert v-for="message in errorMessages" :key="message" type="error" variant="tonal" class="mb-4">
            {{ message }}
        </v-alert>

        <template v-if="task.canSubmit">
            <QuizAnswerForm v-if="isQuiz" :questions="task.questions" :loading="loading" @submit="submit" />
            <FileSubmitForm v-else-if="isFile" :extensions="task.allowedExtensions" :loading="loading"
                @submit="submit" />
            <DiscussionForm v-else :loading="loading" @submit="submit" />
        </template>

        <v-alert v-else-if="task.blockedReason" type="info" variant="tonal" class="mb-4">
            {{ task.blockedReason }}
        </v-alert>

        <SubmissionHistory v-if="task.submissions.length" class="mt-6" :submissions="task.submissions"
            :questions="task.questions" :max-score="task.maxScore" :reveal="task.revealAnswers" />

        <QuizReview v-if="isQuiz && task.revealAnswers" class="mt-6" :questions="task.questions" />
    </div>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import DiscussionForm from "./DiscussionForm.vue";
import FileSubmitForm from "./FileSubmitForm.vue";
import QuizAnswerForm from "./QuizAnswerForm.vue";
import QuizReview from "./QuizReview.vue";
import SubmissionHistory from "./SubmissionHistory.vue";
import LearningController from "../actions/App/Http/Controllers/LearningController";
import { TaskTypes } from "../types/task-types";
import type { LearnTask, QuizPayload } from "../types/learning";
import type { RequestPayload } from "@inertiajs/core";

const props = defineProps<{ studentId: number; task: LearnTask }>();

const loading = ref(false);
const errors = ref<Record<string, string>>({});

const isQuiz = computed(() => props.task.type.value === TaskTypes.QUIZ);
const isFile = computed(() => props.task.type.value === TaskTypes.FILE_UPLOAD);
const errorMessages = computed(() => [...new Set(Object.values(errors.value))]);

type SubmitPayload = QuizPayload | { file: File | null } | { text: string };

function submit(payload: SubmitPayload) {
    router.post(
        LearningController.submit({ student: props.studentId, task: props.task.id }).url,
        payload as unknown as RequestPayload,
        {
            forceFormData: true,
            preserveScroll: true,
            onStart: () => {
                loading.value = true;
                errors.value = {};
            },
            onError: (e) => (errors.value = e),
            onFinish: () => (loading.value = false),
        },
    );
}
</script>