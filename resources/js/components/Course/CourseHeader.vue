<template>
    <v-card class="border" rounded="lg" elevation="0">
        <v-card-text
            class="d-flex justify-space-between align-center ga-4 pa-6 flex-wrap"
        >
            <div>
                <h1 class="text-h5 font-weight-bold">{{ course.title }}</h1>
                <p class="text-body-1 text-medium-emphasis mb-0">
                    {{ course.description }}
                </p>
            </div>

            <div class="d-flex ga-2 flex-shrink-0">
                <v-chip color="primary" variant="tonal">{{
                    course.category.label
                }}</v-chip>
                <v-chip
                    :color="courseStatusColor[course.status.value]"
                    variant="tonal"
                >
                    {{ course.status.label }}
                </v-chip>
            </div>
        </v-card-text>

        <v-divider />

        <v-card-text
            class="d-flex align-center ga-6 px-6 py-4 text-body-2 text-medium-emphasis"
        >
            <span
                ><v-icon size="small" class="me-2">mdi-account-multiple</v-icon
                >{{ activeStudents }} students enrolled</span
            >
            <span
                ><v-icon size="small" class="me-2">mdi-book</v-icon
                >{{ course.lessons.length }} lessons</span
            >
        </v-card-text>
    </v-card>
</template>

<script setup lang="ts">
import { computed } from "vue";
import type { CourseDetail } from "@/types/course";
import { courseStatusColor } from "@/utils/courseStatus";

const props = defineProps<{ course: CourseDetail }>();

const activeStudents = computed(
    () => props.course.students.filter((s) => s.isActive).length,
);
</script>
