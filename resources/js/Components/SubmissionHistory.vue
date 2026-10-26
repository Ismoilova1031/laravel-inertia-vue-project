<template>
    <div>
        <h3 class="text-subtitle-1 font-weight-bold mb-2">Your attempts</h3>

        <v-expansion-panels variant="accordion" class="border rounded-lg">
            <v-expansion-panel v-for="s in submissions" :key="s.attempt" elevation="0">
                <v-expansion-panel-title>
                    <div class="d-flex align-center flex-grow-1 ga-3 flex-wrap">
                        <span class="font-weight-medium">Attempt {{ s.attempt }}</span>
                        <span v-if="s.score !== null && maxScore > 0" class="text-medium-emphasis">
                            {{ s.score }} / {{ maxScore }}
                        </span>
                        <span class="text-body-2 text-medium-emphasis">{{ s.submittedAt }}</span>
                        <v-spacer />
                        <v-chip size="small" variant="tonal" :color="statusColor(s.status.value)">
                            {{ s.status.label }}
                        </v-chip>
                    </div>
                </v-expansion-panel-title>

                <v-expansion-panel-text>
                    <p v-if="s.hasFile" class="text-body-2 mb-2">
                        <v-icon size="small" icon="mdi-paperclip" /> File uploaded
                    </p>
                    <p v-if="s.content" class="text-body-2 mb-2" style="white-space: pre-wrap">{{ s.content }}</p>

                    <v-alert v-if="s.teacherComment" type="info" variant="tonal" density="compact" class="mb-3">
                        <strong>Teacher:</strong> {{ s.teacherComment }}
                    </v-alert>

                    <div v-for="row in visibleAnswers(s)" :key="row.question.id" class="mb-3">
                        <div class="d-flex justify-space-between ga-4">
                            <span class="font-weight-medium text-body-2">{{ row.question.text }}</span>
                            <v-chip v-if="row.answer.score !== null" size="x-small" variant="tonal">
                                {{ row.answer.score }} / {{ row.question.points }}
                            </v-chip>
                            <v-chip v-else size="x-small" color="warning" variant="tonal">Waiting for review</v-chip>
                        </div>
                        <p class="text-body-2 text-medium-emphasis mb-1" style="white-space: pre-wrap">
                            {{ row.given }}
                        </p>
                        <v-alert
                            v-if="row.answer.teacherComment"
                            type="info"
                            variant="tonal"
                            density="compact"
                        >
                            <strong>Teacher:</strong> {{ row.answer.teacherComment }}
                        </v-alert>
                    </div>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </div>
</template>

<script setup lang="ts">
import { QuestionType } from "../types/question-types";
import type { LearnAnswer, LearnQuestion, LearnSubmission } from "../types/learning";

const props = defineProps<{
    submissions: LearnSubmission[];
    questions: LearnQuestion[];
    maxScore: number;
    reveal: boolean;
}>();

const statusColor = (status: number) => (status === 1 ? "success" : status === 2 ? "error" : "warning");

interface AnswerRow {
    question: LearnQuestion;
    answer: LearnAnswer;
    given: string;
}

function visibleAnswers(submission: LearnSubmission): AnswerRow[] {
    const rows: AnswerRow[] = [];

    for (const answer of submission.answers) {
        const question = props.questions.find((q) => q.id === answer.questionId);
        if (!question) continue;

        const isOpen = question.type.value === QuestionType.OPEN_ENDED;
        if (!isOpen && !props.reveal) continue;

        const picked = question.options
            .filter((o) => answer.selectedOptionIds.includes(o.id))
            .map((o) => o.text);

        rows.push({ question, answer, given: picked.length ? picked.join(", ") : (answer.text ?? "No answer") });
    }

    return rows;
}
</script>