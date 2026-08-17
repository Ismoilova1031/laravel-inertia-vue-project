<template>
    <v-container class="py-8">
        <v-card max-width="900" class="mx-auto" rounded="lg" elevation="2">
            <v-card-item>
                <v-card-title class="text-h4 font-weight-bold">
                    {{ course.title }}
                </v-card-title>

                <v-card-subtitle class="mt-2"> Course #{{ course.id }} </v-card-subtitle>
            </v-card-item>

            <v-card-text class="pa-6">
                <div class="d-flex ga-2 mb-6">
                    <v-chip color="primary" variant="tonal">
                        {{ course.category }}
                    </v-chip>

                    <v-chip color="success" variant="tonal">
                        {{ course.status }}
                    </v-chip>
                </div>

                <h2 class="text-h6 mb-3">Description</h2>

                <p class="text-body-1 text-medium-emphasis">
                    {{ course.description }}
                </p>
            </v-card-text>

            <v-divider />

            <v-card-actions class="px-6 py-4">
                <Link :href="DashboardController.index().url">
                    <v-btn variant="text" prepend-icon="mdi-arrow-left"> Back to Dashboard </v-btn>
                </Link>

                <v-spacer />

                <Link :href="CourseController.edit(course.id).url">
                    <v-btn color="primary" prepend-icon="mdi-pencil"> Edit Course </v-btn>
                </Link>
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import type { Course } from "../../types/course";
import CourseController from "../../actions/App/Http/Controllers/CourseController";
import DashboardController from "../../actions/App/Http/Controllers/DashboardController";

defineProps<{
    course: Course;
}>();
</script>
