<template>
  <v-container class="py-8">
    <v-card max-width="900" class="mx-auto" rounded="lg" elevation="2">
      <v-card-item>
        <v-card-title class="text-h4 font-weight-bold">Edit Course</v-card-title>

        <v-card-subtitle class="mt-2">Update the course information below.</v-card-subtitle>

      </v-card-item>

      <v-divider />
      
      <v-card-text class="pa-6">
        <CourseForm :form="form" :categories="categories" :statuses="statuses" submitLabel="Update Course" :submit="submit" />
      </v-card-text>
    </v-card>
  </v-container>
</template>
<script setup lang="ts">
import CourseForm from "../../Components/CourseForm.vue";
import { useCourseForm } from "../../forms/courseForm";

const props = defineProps<{
    course: {
        id: number;
        title: string;
        description: string;
        category: {
            title: string;
            value: number;
        };
        status: {
            title: string;
            value: number;
        };
    };
    categories: {
        title: string;
        value: number;
    }[];
    statuses: {
        title: string;
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
