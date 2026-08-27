<template>
  <v-card>
    <v-card-title>Questions</v-card-title>

    <div v-if="errors?.questions" class="text-error text-caption px-4 pb-2">
      {{ errors.questions }}
    </div>

    <v-table>
      <thead>
        <tr>
          <th>Order</th>
          <th>Question</th>
          <th>Type</th>
          <th>Points</th>
          <th class="text-end pr-8">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(question, index) in questions" :key="question.id ?? index">
          <td>
            <v-icon class="drag-handle" color="medium-emphasis" size="20">
              mdi-menu
            </v-icon>
            {{ index + 1 }}
          </td>
          <td>{{ question.question }}</td>
          <td>{{ question.type }}</td>
          <td>{{ question.points }}</td>
          <td class="text-end">
            <v-btn icon="mdi-pencil" variant="text" size="small" @click="editQuestion(index)" />
            <v-btn icon="mdi-delete" variant="text" size="small" @click="removeQuestion(index)" />
          </td>
        </tr>
      </tbody>
    </v-table>
  </v-card>
  <v-card v-if="!isQuestionFormVisible" link rounded="lg" class="border border-dashed bg-transparent mt-4" elevation="0"
    @click="openQuestionForm">
    <div class="d-flex align-center justify-center px-5 py-3">
      <v-btn icon="mdi-plus" variant="text" size="medium" class="mr-4" />
      <span class="font-weight-medium"> Add Question </span>
    </div>
  </v-card>

  <QuestionForm v-if="isQuestionFormVisible" v-model="activeQuestion" :errors="activeQuestionErrors"
    @cancel="closeQuestionForm" @save="saveQuestion" class="mt-4" />
</template>
<script setup lang="ts">
import { ref, computed } from "vue";
import QuestionForm from "./QuestionForm.vue";
import type { QuestionFormData } from "../forms/questionForm";

const props = defineProps<{
  errors?: Record<string, string>;
}>();

const questions = defineModel<QuestionFormData[]>({
  required: true,
});

const isQuestionFormVisible = ref(false);
const editingIndex = ref<number | null>(null);

const draftQuestion = ref<QuestionFormData>(emptyQuestion());

function emptyQuestion(): QuestionFormData {
  return {
    question: "",
    type: 1,
    points: 1,
    options: [],
    correct_answer: null,
  };
}

const activeQuestion = computed({
  get() {
    return editingIndex.value !== null
      ? questions.value[editingIndex.value]
      : draftQuestion.value;
  },
  set(value: QuestionFormData) {
    if (editingIndex.value !== null) {
      questions.value[editingIndex.value] = value;
    } else {
      draftQuestion.value = value;
    }
  },
});

const activeQuestionErrors = computed(() => {
  const index = editingIndex.value ?? questions.value.length;
  const prefix = `questions.${index}.`;
  const result: Record<string, string> = {};

  for (const key in props.errors ?? {}) {
    if (key.startsWith(prefix)) {
      result[key.replace(prefix, "")] = props.errors![key];
    }
  }
  return result;
});

function openQuestionForm() {
  editingIndex.value = null;
  draftQuestion.value = emptyQuestion();
  isQuestionFormVisible.value = true;
}

function editQuestion(index: number) {
  editingIndex.value = index;
  isQuestionFormVisible.value = true;
}

function saveQuestion() {
  if (editingIndex.value === null) {
    questions.value = [...questions.value, draftQuestion.value];
  }

  closeQuestionForm();
}

function closeQuestionForm() {
  isQuestionFormVisible.value = false;
  editingIndex.value = null;
}

function removeQuestion(index: number) {
  questions.value = questions.value.filter((_, i) => i !== index);
}
</script>
