<template>
    <v-dialog :model-value="!!submission" :max-width="isFile ? 900 : 760" scrollable @update:model-value="emit('close')">
        <v-card v-if="submission" rounded="lg">
            <v-card-title class="pa-5">
                <div class="d-flex justify-space-between align-start ga-4">
                    <div>
                        {{ submission.student.fullName }} · {{ submission.lesson.title }}
                        <div class="text-body-2 text-medium-emphasis">
                            Attempt {{ submission.attempt }} · {{ submission.submittedAt }}
                        </div>
                    </div>
                    <div class="d-flex ga-2">
                        <v-chip v-if="submission.score !== null" color="primary" variant="tonal">
                            {{ submission.score }} / {{ submission.maxScore }}
                        </v-chip>
                        <v-chip variant="tonal" :color="statusColor(submission.status.value)">
                            {{ submission.status.label }}
                        </v-chip>
                    </div>
                </div>
            </v-card-title>

            <v-divider />

            <v-card-text class="pa-5">
                <v-alert v-for="m in errorMessages" :key="m" type="error" variant="tonal" class="mb-4">{{ m }}</v-alert>

                <template v-if="isFile">
                    <SubmissionFilePreview
                        v-if="submission.hasFile"
                        :submission-id="submission.id"
                        :extension="submission.fileExtension"
                    />

                    <template v-if="submission.status.value === 0">
                        <v-textarea
                            v-model="comment"
                            label="Comment (required to reject)"
                            variant="outlined"
                            rows="2"
                            auto-grow
                            class="mt-4"
                            hide-details
                        />
                    </template>
                    <v-alert v-else-if="submission.teacherComment" type="info" variant="tonal" class="mt-4">
                        {{ submission.teacherComment }}
                    </v-alert>
                </template>

                <p v-else-if="submission.taskType.value === TaskTypes.DISCUSSION" class="text-body-1" style="white-space: pre-wrap">
                    {{ submission.content }}
                </p>

                <template v-else>
                    <OpenAnswerGrader
                        v-for="a in openAnswers"
                        :key="a.questionId"
                        :answer="a"
                        :loading="savingId === a.questionId"
                        @save="grade(a, $event)"
                    />

                    <v-expansion-panels v-if="autoAnswers.length" variant="accordion" class="border rounded-lg mt-2">
                        <v-expansion-panel elevation="0">
                            <v-expansion-panel-title>
                                Auto-graded answers ({{ autoAnswers.length }})
                            </v-expansion-panel-title>
                            <v-expansion-panel-text>
                                <div v-for="a in autoAnswers" :key="a.questionId" class="mb-3">
                                    <div class="d-flex justify-space-between ga-4">
                                        <span class="font-weight-medium text-body-2">{{ a.question }}</span>
                                        <v-chip size="x-small" variant="tonal" :color="a.score === a.points ? 'success' : 'error'">
                                            {{ a.score ?? 0 }} / {{ a.points }}
                                        </v-chip>
                                    </div>
                                    <p class="text-body-2 text-medium-emphasis mb-0">
                                        {{ a.selected.length ? a.selected.join(", ") : (a.text ?? "No answer") }}
                                    </p>
                                </div>
                            </v-expansion-panel-text>
                        </v-expansion-panel>
                    </v-expansion-panels>

                    <p v-if="!openAnswers.length && !autoAnswers.length" class="text-body-2 text-medium-emphasis">
                        No answers submitted.
                    </p>
                </template>
            </v-card-text>

            <v-divider />

            <v-card-actions class="pa-4">
                <v-btn variant="text" @click="emit('close')">Close</v-btn>
                <v-spacer />
                <template v-if="isFile && submission.status.value === 0">
                    <v-btn color="error" variant="tonal" :loading="reviewing" @click="review(false)">Reject</v-btn>
                    <v-btn color="success" variant="flat" :loading="reviewing" @click="review(true)">Accept</v-btn>
                </template>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import OpenAnswerGrader from "./OpenAnswerGrader.vue";
import SubmissionFilePreview from "./SubmissionFilePreview.vue";
import SubmissionController from "@/actions/App/Http/Controllers/SubmissionController";
import { QuestionType } from "@/types/question.js";
import { TaskTypes } from "@/types/task.js";
import type { SubmissionAnswerRow, SubmissionRow } from "@/types/submission";

const props = defineProps<{ submission: SubmissionRow | null }>();
const emit = defineEmits<{ close: [] }>();

const comment = ref("");
const errors = ref<Record<string, string>>({});
const reviewing = ref(false);
const savingId = ref<number | null>(null);

const isFile = computed(() => props.submission?.taskType.value === TaskTypes.FILE_UPLOAD);
const errorMessages = computed(() => [...new Set(Object.values(errors.value))]);

const isOpen = (a: SubmissionAnswerRow) => a.questionType === QuestionType.OPEN_ENDED;

const openAnswers = computed(() =>
    (props.submission?.answers ?? [])
        .filter((a) => isOpen(a) && a.text !== null)
        .sort((a, b) => Number(a.score !== null) - Number(b.score !== null)),
);
const autoAnswers = computed(() => (props.submission?.answers ?? []).filter((a) => !isOpen(a)));

watch(
    () => props.submission?.id,
    () => {
        comment.value = "";
        errors.value = {};
    },
);

const statusColor = (status: number) => (status === 1 ? "success" : status === 2 ? "error" : "warning");

function review(accept: boolean) {
    if (!props.submission) return;

    router.post(
        SubmissionController.review({ submission: props.submission.id }).url,
        { accept, comment: comment.value },
        {
            preserveScroll: true,
            onStart: () => {
                reviewing.value = true;
                errors.value = {};
            },
            onError: (e) => (errors.value = e),
            onSuccess: () => emit("close"),
            onFinish: () => (reviewing.value = false),
        },
    );
}

function grade(answer: SubmissionAnswerRow, payload: { score: number; comment: string }) {
    if (!props.submission) return;

    router.post(
        SubmissionController.grade({ submission: props.submission.id, question: answer.questionId }).url,
        payload,
        {
            preserveScroll: true,
            onStart: () => {
                savingId.value = answer.questionId;
                errors.value = {};
            },
            onError: (e) => (errors.value = e),
            onFinish: () => (savingId.value = null),
        },
    );
}
</script>