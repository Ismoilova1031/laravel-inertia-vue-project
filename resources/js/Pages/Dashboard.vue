<template>
  <v-container>
    <h1 class="mb-6">Study Planner</h1>
    <Link :href="CourseController.create().url">
      <v-btn>
        Create Course
      </v-btn>
    </Link>
    <v-row>
      <v-col v-for="course in courses" :key="course.id" cols="12" md="6" lg="4">
        <v-card>
          <v-card-title>{{ course.title }}</v-card-title>
          <v-card-text>{{ course.description }}</v-card-text>
          <v-card-actions>
            <Link :href="CourseController.show(course.id).url" preserve-scroll>
              <v-btn> View Course </v-btn>
            </Link>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import type { Course } from "../types/course";
import CourseController from "../actions/App/Http/Controllers/CourseController";

defineProps<{
  courses: Course[];
}>();
</script>
