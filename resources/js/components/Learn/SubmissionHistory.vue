<template>
    <div>
        <h3 class="text-subtitle-1 font-weight-bold mb-2">Your attempts</h3>

        <v-expansion-panels variant="accordion" class="border rounded-lg">
            <v-expansion-panel
                v-for="s in submissions"
                :key="s.attempt"
                elevation="0"
            >
                <v-expansion-panel-title>
                    <div class="d-flex align-center flex-grow-1 ga-3 flex-wrap">
                        <span class="font-weight-medium"
                            >Attempt {{ s.attempt }}</span
                        >
                        <span
                            v-if="s.score !== null && maxScore > 0 && reveal"
                            class="text-medium-emphasis"
                        >
                            {{ s.score }} / {{ maxScore }}
                        </span>
                        <span class="text-body-2 text-medium-emphasis">{{
                            s.submittedAt
                        }}</span>
                        <v-spacer />
                        <v-chip
                            size="small"
                            variant="tonal"
                            :color="submissionStatusColor(s.status.value)"
                        >
                            {{ s.status.label }}
                        </v-chip>
                    </div>
                </v-expansion-panel-title>

                <v-expansion-panel-text>
                    <p v-if="s.hasFile" class="text-body-2 mb-3">
                        <v-icon size="small" icon="mdi-paperclip" /> File
                        uploaded
                    </p>
                    <p v-if="s.content" class="text-body-2 mb-4 content">
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

                    <SubmissionAnswerRow
                        v-for="row in rows(s)"
                        :key="row.question.id"
                        :question="row.question"
                        :answer="row.answer"
                        :reveal="reveal"
                    />
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </div>
</template>

<script setup lang="ts">
import SubmissionAnswerRow from "@/components/Learn/SubmissionAnswerRow.vue";
import type {
    LearnAnswer,
    LearnQuestion,
    LearnSubmission,
} from "@/types/learning";
import { submissionStatusColor } from "@/utils/submissionStatus";

const props = defineProps<{
    submissions: LearnSubmission[];
    questions: LearnQuestion[];
    maxScore: number;
    reveal: boolean;
}>();

function rows(
    submission: LearnSubmission,
): { question: LearnQuestion; answer: LearnAnswer }[] {
    return submission.answers.flatMap((answer) => {
        const question = props.questions.find(
            (q) => q.id === answer.questionId,
        );

        return question ? [{ question, answer }] : [];
    });
}
</script>

<style scoped>
.content {
    white-space: pre-wrap;
}
</style>
