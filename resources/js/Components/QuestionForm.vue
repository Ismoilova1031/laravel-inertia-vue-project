<template>
    <v-card class="border rounded-lg pa-5 ma-0 md:ma-5">
        <form>
            <v-textarea label="Question" variant="outlined" rows="3" placeholder="Enter your question here..." />

            <v-row>
                <v-col cols="12" md="4">
                    <v-select v-model="form.type" label="Question Type" :items="questionTypes" variant="outlined" />
                </v-col>
                <v-col cols="12" md="4">
                    <v-text-field v-model="form.points" label="Points value" type="number" variant="outlined" />
                </v-col>
            </v-row>

            <OptionList v-if="
                form.type === 'single_choice' ||
                form.type === 'multiple_choice'
            " v-model="form.options" :type="form.type" />

            <v-text-field v-if="form.type === 'short_answer'" v-model="form.correct_answer" label="Correct Answer"
                variant="outlined" placeholder="Enter the expected answer" />

            <v-alert v-if="form.type === 'open_ended'" type="info" variant="tonal">
                Open-ended questions are manually graded by the teacher.
            </v-alert>
        </form>
    </v-card>
</template>
<script setup lang="ts">
import { reactive } from 'vue'
import OptionList from './OptionList.vue'
const form = reactive({
    question: '',
    type: '',
    points: 0,
    options: [],
    correct_answer: ''
})

const questionTypes = [
    { title: 'Single Choice', value: 'single_choice' },
    { title: 'Multiple Choice', value: 'multiple_choice' },
    { title: 'Short Answer', value: 'short_answer' },
    { title: 'Open Ended', value: 'open_ended' },
]
</script>