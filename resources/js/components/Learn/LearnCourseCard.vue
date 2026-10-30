<template>
    <v-card
        height="100%"
        rounded="lg"
        class="d-flex flex-column"
        :class="{ 'inactive-course': !course.isActive }"
        :elevation="2"
    >
        <v-card-item>
            <v-card-title class="text-h6 font-weight-bold text-wrap">
                {{ course.title }}
            </v-card-title>
            <v-card-subtitle class="mt-1">{{ course.category }}</v-card-subtitle>
            <template #append>
                <v-chip v-if="!course.isActive" size="small" color="error" variant="outlined">
                    Access removed
                </v-chip>
                <v-chip v-else-if="isFinished" size="small" color="success" variant="tonal">
                    Completed
                </v-chip>
            </template>
        </v-card-item>

        <v-card-text class="flex-grow-1">
            <p class="text-body-2 text-medium-emphasis description">
                {{ course.description }}
            </p>
        </v-card-text>

        <v-card-text class="pt-0">
            <div class="d-flex justify-space-between text-body-2 mb-1">
                <span class="text-medium-emphasis">
                    {{ course.progress.completed }} / {{ course.progress.total }} lessons
                </span>
                <span class="font-weight-medium">{{ course.progress.percent }}%</span>
            </div>
            <v-progress-linear
                :model-value="course.progress.percent"
                :color="isFinished ? 'success' : 'primary'"
                rounded
                height="8"
            />
        </v-card-text>

        <v-card-actions class="pa-4 pt-0 ga-2">
            <v-btn variant="outlined" class="flex-grow-1" @click="emit('open')">
                {{ course.isActive ? "Details" : "View results" }}
            </v-btn>
            <v-btn
                v-if="course.isActive && course.nextLessonId"
                color="primary"
                variant="flat"
                class="flex-grow-1"
                @click="emit('continue')"
            >
                {{ course.progress.completed === 0 ? "Start" : "Continue" }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script setup lang="ts">
import { computed } from "vue";
import type { LearnCourseItem } from "../../types/learning";

const props = defineProps<{ course: LearnCourseItem }>();

const emit = defineEmits<{ open: []; continue: [] }>();

const isFinished = computed(
    () => props.course.progress.total > 0 && props.course.progress.percent === 100,
);
</script>

<style scoped>
.inactive-course {
    opacity: 0.55;
}

.description {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>