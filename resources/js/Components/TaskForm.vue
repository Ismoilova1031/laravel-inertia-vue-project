<template>
  <v-card class="pa-5 ma-0 md:ma-5 border">
    <v-card-title class="text-title-medium pa-2">
      <v-icon icon="mdi-shield-alert-outline" />
      <span class="ml-2">Task Configuration Fields (Conditional)</span>
    </v-card-title>

    <v-divider />
    <form>
      <v-row class="mt-4">
        <v-col cols="12" md="8">
          <v-select
            label="Task Type"
            :items="taskTypes"
            v-model="task.task_type"
            variant="outlined"
            :error-messages="errors?.task_type"
          />
        </v-col>

        <v-col cols="12" md="4">
          <v-text-field
            label="Deadline"
            :min="minDateTime"
            type="datetime-local"
            variant="outlined"
            v-model="task.deadline"
            icon-left
            :error-messages="errors?.deadline"
          />
        </v-col>
      </v-row>
      <v-select
        v-if="task.task_type === TaskTypes.FILE_UPLOAD"
        max-width="500"
        label="Allowed file extensions"
        :items="fileExtensions"
        multiple
        v-model="task.file_extensions"
        :error-messages="errors?.file_extensions"
      />

      <QuestionList
        v-if="task.task_type === TaskTypes.QUIZ"
        v-model="questions"
        :errors="questionListErrors"
      />
    </form>
  </v-card>
</template>
<script setup lang="ts">
import { computed } from "vue";
import { TaskTypes } from "../types/task-types";
import { FileExtensions } from "../types/file-extensions";
import QuestionList from "./QuestionList.vue";
import type { TaskFormData } from "../forms/taskForm";

const task = defineModel<TaskFormData>({
  required: true,
});
console.log("task:", task.value);
const props = defineProps<{
  errors?: Record<string, string>;
}>();
const taskTypes = Object.entries(TaskTypes)
  .filter(([key]) => isNaN(Number(key)))
  .map(([key, value]) => ({
    title: key,
    value,
  }));
const fileExtensions = Object.entries(FileExtensions).map(([key, value]) => ({
  title: key,
  value,
}));

const minDateTime = new Date().toISOString().slice(0, 16);

const questionListErrors = computed(() => {
  const errors = props.errors ?? {};
  const result: Record<string, string> = {};

  for (const key in errors) {
    if (key === "questions" || key.startsWith("questions.")) {
      result[key] = errors[key]!;
    }
  }
  return result;
});

const questions = computed({
  get: () => task.value.questions ?? [],
  set: (value) => {
    task.value.questions = value;
  },
});
</script>
