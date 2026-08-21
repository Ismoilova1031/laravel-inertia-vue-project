<template>
    <v-card class="border rounded-lg pa-5 ma-0 md:ma-5">
        <v-textarea v-model="question.question" label="Question" variant="outlined" rows="3"
            placeholder="Enter your question here..." :error-messages="mergedErrors?.question" />

        <v-row>
            <v-col cols="12" md="4">
                <v-select v-model="question.type" label="Question Type" :items="questionTypes" variant="outlined"
                    :error-messages="mergedErrors?.type" />
            </v-col>
            <v-col cols="12" md="4">
                <v-text-field v-model.number="question.points" label="Points value" type="number" variant="outlined"
                    :error-messages="mergedErrors?.points" />
            </v-col>
        </v-row>

        <OptionList v-if="
            question.type === 'single_choice' ||
            question.type === 'multiple_choice'
        " v-model="options" :type="question.type" :errors="optionErrors" />

        <v-text-field v-if="question.type === 'short_answer'" v-model="question.correct_answer" label="Correct Answer"
            variant="outlined" placeholder="Enter the expected answer" :error-messages="mergedErrors?.correct_answer" />

        <v-alert v-if="question.type === 'open_ended'" type="info" variant="tonal">
            Open-ended questions are manually graded by the teacher.
        </v-alert>

        <div class="d-flex justify-end ga-2 mt-4">
            <v-btn variant="text" @click="emit('cancel')">Cancel</v-btn>
            <v-btn color="primary" variant="flat" @click="handleSave">Save</v-btn>
        </div>
    </v-card>
</template>
<script setup lang="ts">
import { computed, ref } from 'vue'
import OptionList from './OptionList.vue'
import { questionFormSchema, type QuestionFormData } from '../forms/questionForm'

const props = defineProps<{
    errors?: Record<string, string>;
}>();

const question = defineModel<QuestionFormData>({
    required: true
});

const emit = defineEmits<{
    cancel: [];
    save: [];
}>();

const questionTypes = [
    { title: 'Single Choice', value: 'single_choice' },
    { title: 'Multiple Choice', value: 'multiple_choice' },
    { title: 'Short Answer', value: 'short_answer' },
    { title: 'Open Ended', value: 'open_ended' },
]

const options = computed({
    get: () => question.value.options ?? [],
    set: (value) => {
        question.value.options = value;
    }
});

const localErrors = ref<Record<string, string>>({});

const mergedErrors = computed(() => ({
    ...(props.errors ?? {}),
    ...localErrors.value,
}));

const optionErrors = computed(() => {
    const errors = mergedErrors.value;
    const result: Record<string, string> = {};
    for (const key in errors) {
        if (key.startsWith("options.")) {
            result[key.replace("options.", "")] = errors[key];
        } else if (key === "options") {
            result["options"] = errors[key];
        }
    }
    return result;
});

function handleSave() {
    const result = questionFormSchema.safeParse(question.value);

    if (!result.success) {
        const errs: Record<string, string> = {};
        for (const issue of result.error.issues) {
            const path = issue.path.join(".");
            if (path) errs[path] = issue.message;
        }
        localErrors.value = errs;
        return;
    }

    localErrors.value = {};
    emit('save');
}
</script>