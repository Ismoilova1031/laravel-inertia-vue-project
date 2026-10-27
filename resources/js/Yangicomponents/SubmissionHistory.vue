<template>
    <div>
        <h3 class="text-subtitle-1 font-weight-bold mb-2">
            Your attempts
        </h3>

        <v-expansion-panels variant="accordion" class="border rounded-lg">
            <v-expansion-panel
                v-for="s in submissions"
                :key="s.attempt"
                elevation="0"
            >
                <v-expansion-panel-title>
                    <div class="d-flex align-center flex-grow-1 ga-3 flex-wrap">
                        <span class="font-weight-medium">
                            Attempt {{ s.attempt }}
                        </span>

                        <span
                            v-if="s.score !== null && maxScore > 0"
                            class="text-medium-emphasis"
                        >
                            {{ s.score }} / {{ maxScore }}
                        </span>

                        <span class="text-body-2 text-medium-emphasis">
                            {{ s.submittedAt }}
                        </span>

                        <v-spacer />

                        <v-chip
                            size="small"
                            variant="tonal"
                            :color="statusColor(s.status.value)"
                        >
                            {{ s.status.label }}
                        </v-chip>
                    </div>
                </v-expansion-panel-title>

                <v-expansion-panel-text>
                    <p v-if="s.hasFile" class="text-body-2 mb-3">
                        <v-icon size="small" icon="mdi-paperclip" />
                        File uploaded
                    </p>

                    <p
                        v-if="s.content"
                        class="text-body-2 mb-4"
                        style="white-space: pre-wrap"
                    >
                        {{ s.content }}
                    </p>

                    <v-alert
                        v-if="s.teacherComment"
                        type="info"
                        variant="tonal"
                        density="compact"
                        class="mb-4"
                    >
                        <strong>Teacher:</strong> {{ s.teacherComment }}
                    </v-alert>

                    <div
                        v-for="row in visibleAnswers(s)"
                        :key="row.question.id"
                        class="answer-row py-4"
                    >
                        <div
                            class="d-flex align-start justify-space-between ga-3 mb-3"
                        >
                            <div class="text-body-2 font-weight-medium">
                                {{ row.question.text }}
                            </div>

                            <v-chip
                                v-if="row.answer.score !== null"
                                size="small"
                                variant="tonal"
                                :color="isCorrect(row) === null
                                    ? 'default'
                                    : isCorrect(row)
                                      ? 'success'
                                      : 'error'"
                            >
                                {{ row.answer.score }} / {{ row.question.points }}
                            </v-chip>

                            <v-chip
                                v-else
                                size="small"
                                color="warning"
                                variant="tonal"
                            >
                                Pending
                            </v-chip>
                        </div>

                        <!-- Student's answer -->
                        <div class="text-caption text-medium-emphasis mb-2">
                            Your answer
                        </div>

                        <div class="answer-content pa-3 rounded-lg">
                            <template
                                v-if="selectedOptions(row).length > 0"
                            >
                                <div
                                    v-for="option in selectedOptions(row)"
                                    :key="option.id"
                                    class="d-flex align-center ga-2 mb-1"
                                >
                                    <v-icon
                                        :icon="optionIcon(row, option.id)"
                                        :color="optionColor(row, option.id)"
                                        size="small"
                                    />

                                    <span>{{ option.text }}</span>
                                </div>
                            </template>

                            <span v-else-if="row.answer.text">
                                {{ row.answer.text }}
                            </span>

                            <span v-else class="text-medium-emphasis">
                                No answer provided
                            </span>
                        </div>

                        <!-- Correct answer: revealed after the third attempt -->
                        <div
                            v-if="canRevealAnswers(s)"
                            class="mt-3"
                        >
                            <div class="text-caption text-medium-emphasis mb-2">
                                Correct answer
                            </div>

                            <div
                                v-if="correctOptions(row).length > 0"
                                class="correct-answer pa-3 rounded-lg"
                            >
                                <div
                                    v-for="option in correctOptions(row)"
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
                            </div>

                            <div
                                v-else-if="row.question.type.value === QuestionType.OPEN_ENDED"
                                class="text-body-2 text-medium-emphasis"
                            >
                                This question requires manual review.
                            </div>
                        </div>

                        <!-- Teacher feedback -->
                        <v-alert
                            v-if="row.answer.teacherComment"
                            type="info"
                            variant="tonal"
                            density="compact"
                            class="mt-3"
                        >
                            <strong>Teacher:</strong>
                            {{ row.answer.teacherComment }}
                        </v-alert>
                    </div>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </div>
</template>

<script setup lang="ts">
import { QuestionType } from "../types/question-types";
import type {
    LearnAnswer,
    LearnQuestion,
    LearnSubmission,
} from "../types/learning";

const props = defineProps<{
    submissions: LearnSubmission[];
    questions: LearnQuestion[];
    maxScore: number;
    reveal: boolean;
}>();

const statusColor = (status: number) =>
    status === 1 ? "success" : status === 2 ? "error" : "warning";

interface AnswerRow {
    question: LearnQuestion;
    answer: LearnAnswer;
}

function visibleAnswers(submission: LearnSubmission): AnswerRow[] {
    return submission.answers.flatMap((answer) => {
        const question = props.questions.find(
            (item) => item.id === answer.questionId,
        );

        return question ? [{ question, answer }] : [];
    });
}

function canRevealAnswers(submission: LearnSubmission): boolean {
    return props.reveal || submission.attempt >= 3;
}

function selectedOptions(row: AnswerRow) {
    return row.question.options.filter((option) =>
        row.answer.selectedOptionIds.includes(option.id),
    );
}

function correctOptions(row: AnswerRow) {
    if (row.question.type.value === QuestionType.OPEN_ENDED) {
        return [];
    }

    return row.question.options.filter((option) => option.isCorrect);
}

/**
 * Returns:
 * true  = correct
 * false = incorrect
 * null  = cannot determine automatically
 */
function isCorrect(row: AnswerRow): boolean | null {
    if (row.question.type.value === QuestionType.OPEN_ENDED) {
        return null;
    }

    const correctIds = correctOptions(row)
        .map((option) => option.id)
        .sort();

    const selectedIds = [...row.answer.selectedOptionIds].sort();

    if (correctIds.length === 0) {
        return null;
    }

    return (
        correctIds.length === selectedIds.length &&
        correctIds.every((id, index) => id === selectedIds[index])
    );
}

function optionColor(row: AnswerRow, optionId: number | string): string | undefined {
    const correct = isCorrect(row);

    if (!row.answer.selectedOptionIds.includes(optionId as never)) {
        return undefined;
    }

    if (correct === null) {
        return undefined;
    }

    return correct ? "success" : "error";
}

function optionIcon(row: AnswerRow, optionId: number | string): string {
    if (!row.answer.selectedOptionIds.includes(optionId as never)) {
        return "mdi-circle-outline";
    }

    const correct = isCorrect(row);

    if (correct === null) {
        return "mdi-circle-medium";
    }

    return correct ? "mdi-check-circle" : "mdi-close-circle";
}
</script>

<style scoped>
.answer-row + .answer-row {
    border-top: 1px solid rgba(var(--v-border-color), 0.12);
}

.answer-content {
    background: rgb(var(--v-theme-surface));
    border: 1px solid rgba(var(--v-border-color), 0.2);
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.correct-answer {
    background: rgba(var(--v-theme-success), 0.06);
    border: 1px solid rgba(var(--v-theme-success), 0.2);
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}
</style>