<template>
    <v-card variant="outlined" rounded="lg" class="pa-4 mb-3" :class="{ 'graded-card': answer.score !== null }">
        <div class="d-flex justify-space-between align-start ga-4 mb-2">
            <span class="font-weight-medium">{{ answer.question }}</span>
            <v-chip size="small" :color="answer.score === null ? 'warning' : 'success'" variant="tonal">
                {{ answer.score === null ? "Needs grading" : `Graded ${answer.score} / ${answer.points}` }}
            </v-chip>
        </div>

        <v-sheet color="surface-variant" rounded="lg" class="pa-3 mb-4 student-answer">
            {{ answer.text }}
        </v-sheet>

        <div class="d-flex align-center ga-3">
            <v-slider
                v-model="score"
                :max="answer.points"
                :min="0"
                :step="1"
                thumb-label="always"
                hide-details
                color="primary"
            />
            <span class="text-body-2 text-no-wrap">/ {{ answer.points }}</span>
        </div>

        <div class="d-flex ga-2 mb-3">
            <v-btn size="x-small" variant="tonal" @click="score = 0">0</v-btn>
            <v-btn size="x-small" variant="tonal" @click="score = Math.floor(answer.points / 2)">Half</v-btn>
            <v-btn size="x-small" variant="tonal" @click="score = answer.points">Full</v-btn>
        </div>

        <v-textarea
            v-model="comment"
            label="Comment for the student (optional)"
            variant="outlined"
            density="compact"
            rows="2"
            auto-grow
            hide-details
            class="mb-3"
        />

        <div class="d-flex justify-end">
            <v-btn
                color="primary"
                variant="flat"
                :disabled="!dirty"
                :loading="loading"
                prepend-icon="mdi-content-save-outline"
                @click="emit('save', { score, comment })"
            >
                {{ answer.score === null ? "Save grade" : "Update grade" }}
            </v-btn>
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { computed, ref, watch } from "vue";
import type { SubmissionAnswerRow } from "../types/submission";

const props = defineProps<{ answer: SubmissionAnswerRow; loading: boolean }>();
const emit = defineEmits<{ save: [payload: { score: number; comment: string }] }>();

const score = ref(props.answer.score ?? 0);
const comment = ref(props.answer.teacherComment ?? "");

watch(
    () => [props.answer.score, props.answer.teacherComment],
    () => {
        score.value = props.answer.score ?? 0;
        comment.value = props.answer.teacherComment ?? "";
    },
);

const dirty = computed(
    () =>
        props.answer.score === null ||
        score.value !== props.answer.score ||
        comment.value !== (props.answer.teacherComment ?? ""),
);
</script>

<style scoped>
.student-answer {
    white-space: pre-wrap;
}

.graded-card {
    border-color: rgb(var(--v-theme-success));
}
</style>