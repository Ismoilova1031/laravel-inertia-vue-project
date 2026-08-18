<template>
    <v-card rounded="lg" elevation="2">
        <v-list class="py-0">
            <v-list-item v-for="(lesson, index) in lessons" :key="lesson.id" class="px-5 py-3">
                <template #prepend>
                    <div class="d-flex align-center ga-3">
                        <v-icon color="medim-emphasis" size="20">
                            mdi-drag
                        </v-icon>

                        <span class="font-weight-bold mr-4">
                            {{ index + 1 }}
                        </span>
                    </div>
                </template>

                <v-list-item-title class="font-weight-medium">
                    {{ lesson.title }}
                </v-list-item-title>

                <v-list-item-subtitle class="mt-1">
                    {{ lesson.description }}
                </v-list-item-subtitle>

                <template #append>
                    <div class="d-flex align-center ga-2">
                        <v-chip size="small" variant="tonal" :color="lessonTypeColor(lesson.lessonType)">
                            {{ lessonTypeLabel(lesson.lessonType) }}
                        </v-chip>

                        <v-btn icon="mdi-pencil-outline" variant="text" size="small" />

                        <v-btn icon="mdi-delete-outline" variant="text" size="small" color="error" />
                    </div>
                </template>
            </v-list-item>

            <v-list-item v-if="lessons.length === 0" class="text-center py-10">
                <template #prepend>
                    <v-icon size="40" color="medium-emphasis">
                        mdi-book-open-page-variant
                    </v-icon>
                </template>
                <v-list-item-title>
                    No lessons yet.
                </v-list-item-title>

                <v-list-item-subtitle class="text-body-2 text-medium-emphasis">
                    Add the first lesson to this course.
                </v-list-item-subtitle>
            </v-list-item>
        </v-list>
    </v-card>
</template>
<script setup lang="ts">
import type { Lesson } from "../types/lesson";

defineProps<{
    lessons: Lesson[];
}>();

const lessonTypeLabel = (type: number): string => {
    switch (type) {
        case 1:
            return "Video";
        case 2:
            return "Text";
        case 3:
            return "Task";
        default:
            return "Unknown";
    }
};

const lessonTypeColor = (type: number): string => {
    switch (type) {
        case 1:
            return "red";
        case 2:
            return "blue";
        case 3:
            return "orange";
        default:
            return "grey";
    }
};
</script>