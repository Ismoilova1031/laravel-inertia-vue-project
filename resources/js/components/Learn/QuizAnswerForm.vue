<template>
    <form @submit.prevent="emit('submit', buildPayload())">
        <v-card
            v-for="(question, index) in questions"
            :key="question.id"
            variant="outlined"
            rounded="lg"
            class="pa-4 mb-4"
        >
            <div class="d-flex justify-space-between ga-4 mb-2">
                <span class="font-weight-medium">{{ index + 1 }}. {{ question.text }}</span>
                <v-chip size="small" variant="tonal">{{ question.points }} pts</v-chip>
            </div>

            <v-radio-group
                v-if="question.type.value === QuestionType.SINGLE_CHOICE"
                v-model="single[question.id]"
                hide-details
            >
                <v-radio v-for="o in question.options" :key="o.id" :label="o.text" :value="o.id" />
            </v-radio-group>

            <div v-else-if="question.type.value === QuestionType.MULTIPLE_SELECT">
                <v-checkbox
                    v-for="o in question.options"
                    :key="o.id"
                    v-model="multi[question.id]"
                    :label="o.text"
                    :value="o.id"
                    hide-details
                    density="compact"
                />
            </div>

            <v-text-field
                v-else-if="question.type.value === QuestionType.SHORT_ANSWER"
                v-model="texts[question.id]"
                label="Your answer"
                variant="outlined"
                hide-details
            />

            <v-textarea
                v-else
                v-model="texts[question.id]"
                label="Your answer"
                variant="outlined"
                rows="4"
                hide-details
            />
        </v-card>

        <div class="d-flex justify-end">
            <v-btn type="submit" color="primary" :loading="loading" prepend-icon="mdi-send">
                Submit answers
            </v-btn>
        </div>
    </form>
</template>

<script setup lang="ts">
import { reactive } from "vue";
import { QuestionType } from "@/types/question";
import type { LearnQuestion, QuizPayload } from "@/types/learning";

const props = defineProps<{ questions: LearnQuestion[]; loading: boolean }>();
const emit = defineEmits<{ submit: [payload: QuizPayload] }>();

const single = reactive<Record<number, number | null>>({});
const multi = reactive<Record<number, number[]>>({});
const texts = reactive<Record<number, string>>({});

props.questions.forEach((q) => {
    single[q.id] = null;
    multi[q.id] = [];
    texts[q.id] = "";
});

function buildPayload(): QuizPayload {
    const answers: QuizPayload["answers"] = {};

    for (const q of props.questions) {
        const options =
            q.type.value === QuestionType.SINGLE_CHOICE
                ? single[q.id] !== null ? [single[q.id] as number] : []
                : q.type.value === QuestionType.MULTIPLE_SELECT ? multi[q.id] : [];

        answers[q.id] = { options, text: texts[q.id] };
    }

    return { answers };
}
</script>