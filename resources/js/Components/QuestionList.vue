<template>
    <v-card>
        <v-card-title>Questions</v-card-title>
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
                <tr v-for="question in questions" :key="question.id">
                    <td>
                        <v-icon class="drag-handle" color="medium-emphasis" size="20">
                            mdi-menu
                        </v-icon>
                        {{ question.sort_order }}
                    </td>
                    <td>{{ question.question }}</td>
                    <td>{{ question.type }}</td>
                    <td>{{ question.points }}</td>
                    <td class="text-end">
                        <v-btn icon="mdi-pencil" variant="text" size="small" />
                        <v-btn icon="mdi-delete" variant="text" size="small" />
                    </td>
                </tr>
            </tbody>
        </v-table>
    </v-card>
    <v-card link rounded="lg" class="border border-dashed bg-transparent mt-4" elevation="0" @click="openQuestionForm">
        <div class="d-flex align-center justify-center px-5 py-3">
            <v-btn icon="mdi-plus" variant="text" size="medium" class="mr-4" />
            <span class="font-weight-medium">
                Add Question
            </span>
        </div>
    </v-card>

    <QuestionForm v-if="isQuestionFormVisible" @cancel="closeQuestionForm" @created="closeQuestionForm" class="mt-4"/>

</template>
<script setup lang="ts">
import { ref } from "vue";
import QuestionForm from "./QuestionForm.vue";

const isQuestionFormVisible = ref(false);

function openQuestionForm() {
    isQuestionFormVisible.value = true;
}

function closeQuestionForm() {
    isQuestionFormVisible.value = false;
}

const questions = ref([
    {
        id: 1,
        sort_order: 1,
        question: "What is Vue.js?",
        type: "Multiple Choice",
        points: 5,
    },
    {
        id: 2,
        sort_order: 2,
        question: "Explain the virtual DOM.",
        type: "Short Answer",
        points: 10,
    },
    {
        id: 3,
        sort_order: 3,
        question: "What are Vue directives?",
        type: "Multiple Choice",
        points: 5,
    },
]);
</script>
