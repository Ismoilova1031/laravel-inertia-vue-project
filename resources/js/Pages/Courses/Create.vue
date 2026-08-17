<template>
  <v-container class="py-8">
    <v-card max-width="900" class="mx-auto" rounded="lg" elevation="2">
      <v-card-item>
        <v-card-title class="text-h4 font-weight-bold">Create Course</v-card-title>

        <v-card-subtitle class="mt-2">Add a new course to your study planner.</v-card-subtitle>

      </v-card-item>

      <v-divider />
      
      <v-card-text class="pa-6">
        <CourseForm :form="form" :categories="categories" :statuses="statuses" submitLabel="Create Course" :submit="submit" />
      </v-card-text>
    </v-card>
  </v-container>
</template>
<script setup lang="ts">
import CourseForm from "../../Components/CourseForm.vue";
import { useCourseForm } from "../../forms/courseForm";

defineProps<{
  categories: {
    title: string;
    value: number;
  }[];
  statuses: {
    title: string;
    value: number;
  }[];
}>();
const { form, submit } = useCourseForm();
</script>
