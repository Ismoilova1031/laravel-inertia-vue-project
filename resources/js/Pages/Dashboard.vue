<template>
  <v-container class="py-8">
    <div class="d-flex align-center justify-space-between mb-8">
      <div>
        <h1 class="text-h3 font-weight-bold">
          Study Planner
        </h1>

        <p class="text-body-1 text-medium-emphasis mt-2">
          Manage your courses and keep your learning organized.
        </p>
      </div>

      <Link :href="CourseController.create().url">
        <v-btn color="primary" prepend-icon="mdi-plus" size="large">
          Create Course
        </v-btn>
      </Link>
    </div>

    <v-row v-if="courses.length">
      <v-col v-for="course in courses" :key="course.id" cols="12" sm="6" lg="4" xl="3">
        <v-hover v-slot="{ isHovering, props }">
          <v-card v-bind="props" height="100%" rounded="lg" :elevation="isHovering ? 6 : 2">
            <v-card-item>
              <v-card-title class="text-h6 font-weight-bold">
                {{ course.title }}
              </v-card-title>

              <v-card-subtitle class="mt-1">
                Course #{{ course.id }}
              </v-card-subtitle>
            </v-card-item>

            <v-card-text class="pa-6">
              <div class="d-flex align-center flex-wrap ga-2 mb-4">
                <v-chip color="primary" size="small" variant="tonal">
                  {{ course.category }}
                </v-chip>

                <v-chip color="success" size="small" variant="tonal">
                  {{ course.status }}
                </v-chip>

                <v-spacer />

                <v-btn icon="mdi-delete" color="error" variant="text" size="small" aria-label="Delete course"
                  @click="openDeleteDialog(course)" />
              </div>

              <p class="text-body-2 text-medium-emphasis">
                {{ course.description }}
              </p>
            </v-card-text>

            <v-card-actions class="pa-4">
              <Link :href="CourseController.show(course.id).url" class="w-100">
                <v-btn color="primary" variant="outlined" block>
                  View Course
                </v-btn>
              </Link>
            </v-card-actions>
          </v-card>
        </v-hover>
      </v-col>
    </v-row>

    <v-card v-else class="pa-8 text-center" variant="outlined" rounded="lg">
      <v-icon icon="mdi-book-open-outline" size="64" class="mb-4" />

      <h2 class="text-h5 mb-2">
        No courses yet
      </h2>

      <p class="text-body-1 text-medium-emphasis mb-6">
        Start by creating your first course.
      </p>

      <Link :href="CourseController.create().url">
        <v-btn color="primary" prepend-icon="mdi-plus">
          Create Course
        </v-btn>
      </Link>
    </v-card>

    <v-dialog v-model="deleteDialog" max-width="500">
      <v-card rounded="lg">
        <v-card-title class="text-h6">
          Delete Course
        </v-card-title>

        <v-card-text>
          Are you sure you want to delete
          <span class="font-weight-bold text-error">
            "{{ selectedCourse?.title }}"
          </span>
          ?

          <br />

          This action cannot be undone.
        </v-card-text>

        <v-card-actions class="px-6 py-4">
          <v-spacer />

          <v-btn variant="text" @click="closeDeleteDialog">
            Cancel
          </v-btn>

          <v-btn color="error" variant="flat" prepend-icon="mdi-delete" @click="confirmDelete">
            Delete
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";
import type { Course } from "../types/course";
import CourseController from "../actions/App/Http/Controllers/CourseController";

const deleteDialog = ref(false);
const selectedCourse = ref<Course | null>(null);

function openDeleteDialog(course: Course) {
  selectedCourse.value = course;
  deleteDialog.value = true;
}

function closeDeleteDialog() {
  deleteDialog.value = false;
  selectedCourse.value = null;
}

function confirmDelete() {
  if (!selectedCourse.value) {
    return;
  }

  router.delete(
    CourseController.destroy(selectedCourse.value.id).url,
    {
      onFinish: closeDeleteDialog,
    }
  );
}

defineProps<{
  courses: Course[];
}>();
</script>
