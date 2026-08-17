<template>
  <v-container class="py-8">
    <v-card max-width="900" class="mx-auto" rounded="lg" elevation="2">
      <v-card-item>
        <v-card-title class="text-h4 font-weight-bold">Edit Course</v-card-title>

        <v-card-subtitle class="mt-2">Update the course information below.</v-card-subtitle>

      </v-card-item>

      <v-divider />

      <v-card-text class="pa-6">
        <CourseForm :form="form" :categories="categories" :statuses="statuses" submit-label="Update Course"
          :submit="submit" />
      </v-card-text>

      <v-divider />

      <v-card-actions class="px-6 py-4">
        <Link :href="CourseController.show(course.id).url">
          <v-btn variant="text" prepend-icon="mdi-arrow-left" class="px-6 py-4">Back to Course</v-btn>
        </Link>
      </v-card-actions>
    </v-card>
  </v-container>
</template>
<script setup lang="ts">
import CourseForm from "../../Components/CourseForm.vue";
import { Link } from "@inertiajs/vue3";
import CourseController from "../../actions/App/Http/Controllers/CourseController";
import { useCourseForm } from "../../forms/courseForm";

const props = defineProps<{
  course: {
    id: number;
    title: string;
    description: string;
    category: {
      label: string;
      value: number;
    };
    status: {
      label: string;
      value: number;
    };
  };
  categories: {
    label: string;
    value: number;
  }[];
  statuses: {
    label: string;
    value: number;
  }[];
}>();
const { form, submit } = useCourseForm({
  'title': props.course.title,
  'description': props.course.description,
  'category': props.course.category.value,
  'status': props.course.status.value,
},
  props.course.id
);
</script>
