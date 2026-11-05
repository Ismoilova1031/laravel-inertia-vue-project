<template>
    <div class="answer-row py-4">
        <div class="d-flex align-start justify-space-between ga-3 mb-3">
            <div class="text-body-2 font-weight-medium">
                {{ question.text }}
            </div>

            <template v-if="showScore">
                <v-chip
                    v-if="answer.score !== null"
                    size="small"
                    variant="tonal"
                    :color="verdictColor"
                >
                    {{ answer.score }} / {{ question.points }}
                </v-chip>
                <v-chip v-else size="small" color="warning" variant="tonal"
                    >Pending</v-chip
                >
            </template>
        </div>

        <div class="text-caption text-medium-emphasis mb-2">Your answer</div>
        <div class="box pa-3 rounded-lg">
            <template v-if="selected.length">
                <div
                    v-for="option in selected"
                    :key="option.id"
                    class="d-flex align-center ga-2 mb-1"
                >
                    <v-icon
                        :icon="selectedIcon"
                        :color="verdictColor"
                        size="small"
                    />
                    <span>{{ option.text }}</span>
                </div>
            </template>
            <span v-else-if="answer.text">{{ answer.text }}</span>
            <span v-else class="text-medium-emphasis">No answer provided</span>
        </div>

        <div
            v-if="
                reveal && !isOpen && (correct.length || question.correctAnswer)
            "
            class="mt-3"
        >
            <div class="text-caption text-medium-emphasis mb-2">
                Correct answer
            </div>
            <div class="box correct pa-3 rounded-lg">
                <div
                    v-for="option in correct"
                    :key="option.id"
                    class="d-flex align-center ga-2 mb-1"
                >
                    <v-icon
                        icon="mdi-check-circle"
                        color="success"
                        size="small"
                    />
                    <span>{{ option.text }}</span>
                </div>
                <span v-if="question.correctAnswer">{{
                    question.correctAnswer
                }}</span>
            </div>
        </div>

        <v-alert
            v-if="answer.teacherComment"
            type="info"
            variant="tonal"
            density="compact"
            class="mt-3"
        >
            <strong>Teacher:</strong> {{ answer.teacherComment }}
        </v-alert>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import type { LearnAnswer, LearnQuestion } from "@/types/learning";
import { QuestionType } from "@/types/question";

const props = defineProps<{
    question: LearnQuestion;
    answer: LearnAnswer;
    reveal: boolean;
}>();

const isOpen = computed(
    () => props.question.type.value === QuestionType.OPEN_ENDED,
);

const showScore = computed(() => isOpen.value || props.reveal);

const selected = computed(() =>
    props.question.options.filter((o) =>
        props.answer.selectedOptionIds.includes(o.id),
    ),
);
const correct = computed(() =>
    props.question.options.filter((o) => o.isCorrect),
);

const verdict = computed<boolean | null>(() =>
    !showScore.value || isOpen.value || props.answer.score === null
        ? null
        : props.answer.score === props.question.points,
);
const verdictColor = computed(() =>
    verdict.value === null ? undefined : verdict.value ? "success" : "error",
);
const selectedIcon = computed(() =>
    verdict.value === null
        ? "mdi-circle-medium"
        : verdict.value
          ? "mdi-check-circle"
          : "mdi-close-circle",
);
</script>

<style scoped>
.answer-row + .answer-row {
    border-top: 1px solid rgba(var(--v-border-color), 0.12);
}

.box {
    background: rgb(var(--v-theme-surface));
    border: 1px solid rgba(var(--v-border-color), 0.2);
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.correct {
    background: rgba(var(--v-theme-success), 0.06);
    border-color: rgba(var(--v-theme-success), 0.2);
}
</style>
