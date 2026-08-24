<template>
  <v-container class="py-8">
    <v-card max-width="900" class="mx-auto" rounded="lg" elevation="2">
      <v-card-item>
        <v-card-title class="text-h4 font-weight-bold">Create Course</v-card-title>

        <v-card-subtitle class="mt-2">Add a new course to your study planner.</v-card-subtitle>

      </v-card-item>

      <v-divider />

      <v-card-text class="pa-6">
        <CourseForm :form="form" :categories="categories" :statuses="statuses" submitLabel="Create Course"
          :submit="submit" />
      </v-card-text>

      <v-divider />

      <v-card-actions class="px-6 py-4">
        <Link :href="DashboardController.index().url">
          <v-btn variant="text" prepend-icon="mdi-arrow-left">Back to Dashboard</v-btn>
        </Link>
      </v-card-actions>
    </v-card>
  </v-container>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import CourseForm from "../../components/CourseForm.vue";
import DashboardController from "../../actions/App/Http/Controllers/DashboardController.ts";
import { useCourseForm } from "../../forms/courseForm.ts";
import type { SelectOption } from "../../types/common.ts";

defineProps<{
  categories: SelectOption[];
  statuses: SelectOption[];
}>();

const { form, submit } = useCourseForm();
</script>
