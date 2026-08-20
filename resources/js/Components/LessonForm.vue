<template>
    <form @submit.prevent="submit">

        <v-text-field name="title" placeholder="e.g. Master Vue 3 Router Guards" v-model="form.title" variant="outlined"
            label="Lesson Title" :error-messages="form.errors.title" />

        <v-textarea name="description" placeholder="Lesson Description" v-model="form.description" variant="outlined"
            label="Lesson Description" rows="4" class="mt-4" :error-messages="form.errors.description" />

        <v-number-input control-variant="split" v-model="form.sort_order" label="Sort Order" :error-messages="form.errors.sort_order" width="300"/>


        <v-tabs v-model="form.lesson_type" class="mt-6">
            <v-tab v-for="type in types" :key="type.value" :value="type.value">
                {{ type.label }}
            </v-tab>
        </v-tabs>

        <v-tabs-window v-model="form.lesson_type" class="mt-4">
            <v-tabs-window-item v-for="type in types" :key="type.value" :value="type.value">
                <RichTextEditor v-if="type.value === LessonType.TEXT" v-model="form.content" :error-messages="form.errors.content"/>

                <LessonVideoUpload v-else-if="type.value === LessonType.VIDEO" v-model="form.video"
                    :error-messages="form.errors.video" />
                
                <TaskForm v-else-if="type.value === LessonType.TASK" />
            </v-tabs-window-item>
        </v-tabs-window>

        <div class="d-flex justify-end mt-6">
            <v-btn type="submit" color="primary" border variant="flat" :loading="form.processing"
                :disabled="form.processing">
                {{ submitLabel }}
            </v-btn>
        </div>

    </form>
</template>

<script setup lang="ts">
import type { LessonFormInstance } from "../forms/lessonForm";
import { LessonType } from "../types/lessonTypes";
import RichTextEditor from "./RichTextEditor.vue";
import LessonVideoUpload from "./LessonVideoUpload.vue";
import TaskForm from "./TaskForm.vue";

defineProps<{
    form: LessonFormInstance;

    types: {
        label: string;
        value: number;
    }[];

    submitLabel: string;

    submit: () => void;
}>();

const emit = defineEmits<{
    cancel: [];
}>();
</script>