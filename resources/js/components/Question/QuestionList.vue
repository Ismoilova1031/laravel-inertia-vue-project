<template>
    <v-card class="border" elevation="0" rounded="lg">
        <v-card-title>Questions</v-card-title>

        <div v-if="errors?.questions" class="text-error text-caption px-4 pb-2">
            {{ errors.questions }}
        </div>

        <v-table v-if="questions.length">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Type</th>
                    <th>Points</th>
                    <th class="text-end pr-8">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(question, index) in questions"
                    :key="question.id ?? index"
                >
                    <td>{{ index + 1 }}</td>
                    <td>{{ question.question }}</td>
                    <td>{{ questionTypeLabel(question.question_type) }}</td>
                    <td>{{ question.points }}</td>
                    <td class="text-end">
                        <v-btn
                            icon="mdi-pencil"
                            variant="text"
                            size="small"
                            aria-label="Edit question"
                            @click="edit(index)"
                        />
                        <v-btn
                            icon="mdi-delete"
                            variant="text"
                            size="small"
                            aria-label="Delete question"
                            @click="remove(index)"
                        />
                    </td>
                </tr>
            </tbody>
        </v-table>
        <v-card-text v-else class="text-medium-emphasis"
            >No questions yet.</v-card-text
        >
    </v-card>

    <AddCard v-if="!draft" class="mt-4" label="Add Question" @click="openNew" />

    <QuestionForm
        v-if="draft"
        class="mt-4"
        :model-value="draft"
        :errors="draftErrors"
        @update:model-value="draft = $event"
        @cancel="close"
        @save="save"
    />
</template>

<script setup lang="ts">
import { computed, ref } from "vue";
import AddCard from "@/components/Common/AddCard.vue";
import QuestionForm from "./QuestionForm.vue";
import type { QuestionFormData } from "@/composables/useTaskForm";
import { questionTypeLabel } from "@/utils/questionType";

const props = defineProps<{ errors?: Record<string, string> }>();
const questions = defineModel<QuestionFormData[]>({ required: true });

const draft = ref<QuestionFormData | null>(null);
const editingIndex = ref<number | null>(null);

const emptyQuestion = (): QuestionFormData => ({
    question: "",
    question_type: 1,
    points: 1,
    options: [],
    correct_answer: null,
});

const clone = <T,>(value: T): T => JSON.parse(JSON.stringify(value));

function openNew() {
    editingIndex.value = null;
    draft.value = emptyQuestion();
}

function edit(index: number) {
    editingIndex.value = index;
    draft.value = clone(questions.value[index]);
}

function save() {
    if (!draft.value) return;

    const next = [...questions.value];
    if (editingIndex.value === null) next.push(draft.value);
    else next[editingIndex.value] = draft.value;

    questions.value = next;
    close();
}

function close() {
    draft.value = null;
    editingIndex.value = null;
}

function remove(index: number) {
    questions.value = questions.value.filter((_, i) => i !== index);
}

const draftErrors = computed(() => {
    const prefix = `questions.${editingIndex.value ?? questions.value.length}.`;
    const result: Record<string, string> = {};

    for (const key in props.errors ?? {}) {
        if (key.startsWith(prefix))
            result[key.slice(prefix.length)] = props.errors![key];
    }

    return result;
});
</script>
