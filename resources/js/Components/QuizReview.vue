<template>
    <div>
        <h3 class="text-subtitle-1 font-weight-bold mb-2">Correct answers</h3>
        <v-card v-for="(q, index) in questions" :key="q.id" variant="outlined" rounded="lg" class="pa-4 mb-3">
            <div class="font-weight-medium mb-2">{{ index + 1 }}. {{ q.text }}</div>

            <div v-for="o in q.options" :key="o.id" class="d-flex align-center ga-2">
                <v-icon
                    size="small"
                    :icon="o.isCorrect ? 'mdi-check-circle' : 'mdi-circle-outline'"
                    :color="o.isCorrect ? 'success' : 'medium-emphasis'"
                />
                <span :class="{ 'font-weight-medium': o.isCorrect }">{{ o.text }}</span>
            </div>

            <div v-if="q.correctAnswer" class="text-body-2">
                Expected: <strong>{{ q.correctAnswer }}</strong>
            </div>
        </v-card>
    </div>
</template>

<script setup lang="ts">
import type { LearnQuestion } from "../types/learning";

defineProps<{ questions: LearnQuestion[] }>();
</script>