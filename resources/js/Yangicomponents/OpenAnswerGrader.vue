<template>
    <v-card
        rounded="lg"
        variant="flat"
        border
        class="answer-card mb-4"
    >
        <!-- Question and status -->
        <div class="card-header pa-4">
            <div class="d-flex align-start justify-space-between ga-3">
                <div class="flex-grow-1 min-width-0">
                    <div class="text-subtitle-1 font-weight-medium">
                        {{ answer.question }}
                    </div>
                    <div class="text-caption text-medium-emphasis mt-1">
                        Maximum {{ answer.points }} points
                    </div>
                </div>

                <v-chip
                    size="small"
                    variant="outlined"
                    :color="answer.score === null ? 'default' : 'success'"
                >
                    {{ answer.score === null ? "Not graded" : "Graded" }}
                </v-chip>
            </div>
        </div>

        <v-divider />

        <div class="pa-4">
            <!-- Student response -->
            <div class="d-flex align-center justify-space-between mb-2">
                <span class="text-overline text-medium-emphasis">
                    STUDENT RESPONSE
                </span>
            </div>

            <div class="answer-text mb-6">
                {{ answer.text || "No answer provided." }}
            </div>

            <v-divider class="mb-5" />

            <!-- Score -->
            <div class="d-flex align-center justify-space-between mb-4">
                <span class="text-subtitle-2">Grade</span>

                <div class="score-display">
                    <span class="score-value">{{ score }}</span>
                    <span class="score-total">/ {{ answer.points }}</span>
                </div>
            </div>

            <div class="d-flex align-center ga-3 mb-2">
                <v-btn
                    icon="mdi-minus"
                    size="small"
                    variant="outlined"
                    :disabled="score <= 0"
                    aria-label="Decrease score"
                    @click="score = Math.max(0, score - 1)"
                />

                <v-slider
                    v-model="score"
                    :min="0"
                    :max="answer.points"
                    :step="1"
                    color="primary"
                    track-size="4"
                    thumb-size="16"
                    hide-details
                    :disabled="answer.points <= 0"
                    class="flex-grow-1"
                />

                <v-btn
                    icon="mdi-plus"
                    size="small"
                    variant="outlined"
                    :disabled="score >= answer.points"
                    aria-label="Increase score"
                    @click="score = Math.min(answer.points, score + 1)"
                />
            </div>

            <div class="d-flex flex-wrap ga-2 mb-6">
                <v-btn
                    size="small"
                    variant="text"
                    :color="score === 0 ? 'primary' : undefined"
                    @click="score = 0"
                >
                    Zero
                </v-btn>

                <v-btn
                    size="small"
                    variant="text"
                    :color="score === Math.floor(answer.points / 2) ? 'primary' : undefined"
                    @click="score = Math.floor(answer.points / 2)"
                >
                    Half credit
                </v-btn>

                <v-btn
                    size="small"
                    variant="text"
                    :color="score === answer.points ? 'primary' : undefined"
                    @click="score = answer.points"
                >
                    Full credit
                </v-btn>
            </div>

            <!-- Feedback -->
            <v-textarea
                v-model="comment"
                label="Feedback"
                placeholder="Write optional feedback..."
                variant="outlined"
                density="comfortable"
                rows="2"
                auto-grow
                hide-details
                class="feedback-field mb-5"
            />

            <!-- Footer -->
            <div class="d-flex align-center justify-space-between ga-3">
                <div class="text-caption text-medium-emphasis">
                    <v-icon
                        v-if="dirty"
                        size="14"
                        class="mr-1"
                    >
                        mdi-circle-edit-outline
                    </v-icon>
                    {{ dirty ? "Changes not saved" : "Up to date" }}
                </div>

                <v-btn
                    color="primary"
                    variant="flat"
                    rounded="md"
                    :disabled="!dirty"
                    :loading="loading"
                    @click="emit('save', { score, comment })"
                >
                    Save grade
                </v-btn>
            </div>
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { computed, ref, watch } from "vue";
import type { SubmissionAnswerRow } from "../types/submission";

const props = defineProps<{
    answer: SubmissionAnswerRow;
    loading: boolean;
}>();

const emit = defineEmits<{
    save: [payload: { score: number; comment: string }];
}>();

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
.answer-card {
    background: rgb(var(--v-theme-surface));
}

.card-header {
    background: transparent;
}

.answer-text {
    font-size: 0.925rem;
    line-height: 1.8;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
    color: rgb(var(--v-theme-on-surface));
}

.score-display {
    display: flex;
    align-items: baseline;
    gap: 5px;
    font-variant-numeric: tabular-nums;
}

.score-value {
    font-size: 1.75rem;
    font-weight: 600;
    line-height: 1.2;
    color: rgb(var(--v-theme-primary));
}

.score-total {
    font-size: 0.875rem;
    color: rgb(var(--v-theme-on-surface-variant));
}

.feedback-field :deep(textarea) {
    line-height: 1.6;
}
</style>