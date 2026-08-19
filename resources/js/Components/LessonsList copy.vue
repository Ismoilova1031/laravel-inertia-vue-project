<template>
    <v-card rounded="lg" class="border" elevation="0">
        <v-list class="py-0">
            <draggable v-model="lessons" item-key="id" handle=".drag-handle" @end="reorderLessons">
                <template #item="{ element: lesson, index }">
                    <v-list-item :key="lesson.id" class="px-5 py-3 border-b">
                        <template #prepend>
                            <div class="d-flex align-center ga-3">
                                <v-icon class="drag-handle" color="medium-emphasis" size="20">
                                    mdi-menu
                                </v-icon>

                                <span class="font-weight mr-4">
                                    {{ String(index + 1).padStart(2, '0') }}
                                </span>
                            </div>
                        </template>

                        <div class="items d-flex align-center justify-space-between">
                            <div>
                                <v-list-item-title class="font-weight-medium">
                                    {{ lesson.title }}
                                </v-list-item-title>

                                <v-list-item-subtitle class="mt-1">
                                    {{ lesson.description }}
                                </v-list-item-subtitle>
                            </div>

                            <v-chip size="small" variant="tonal" :color="lessonTypeColor(lesson.lessonType)">
                                {{ lessonTypeLabel(lesson.lessonType) }}
                            </v-chip>
                        </div>

                        <template #append>
                            <div class="d-flex align-center ga-3">
                                <v-btn icon="mdi-pencil-outline" variant="text" size="medium" />

                                <v-btn icon="mdi-delete-outline" variant="text" size="medium" />
                            </div>
                        </template>
                    </v-list-item>
                </template>
            </draggable>

            <v-list-item v-if="lessons.length === 0" class=" text-center py-10">
                <v-icon size="40" color="medium-emphasis">
                    mdi-book-open-page-variant
                </v-icon>
                <v-list-item-title>
                    No lessons yet.
                </v-list-item-title>

                <v-list-item-subtitle class="text-body-2 text-medium-emphasis">
                    Add the first lesson to this course.
                </v-list-item-subtitle>
            </v-list-item>
        </v-list>
    </v-card>
    <Link :href="LessonController.create({ course: course.id }).url" class="add-lesson-link">
        <v-card link rounded="lg" class="border border-dashed bg-transparent mt-4" elevation="0">
            <div class="d-flex align-center justify-center px-5 py-3">
                <v-btn icon="mdi-plus" variant="text" size="medium" class="mr-4" />

                <span class="font-weight-medium">
                    Add Lesson
                </span>
            </div>
        </v-card>
    </Link>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import type { Lesson } from "../types/lesson";
import LessonController from "../actions/App/Http/Controllers/LessonController";
import type { Course } from "../types/course";
import draggable from "vuedraggable";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    lessons: Lesson[];
    course: Course;
}>();

const lessons = ref([...props.lessons]);

const reorderLessons = () => {
    router.put(
        `/courses/${props.course.id}/lessons/reorder`,
        {
            lessons: lessons.value.map((lesson, index) => ({
                id: lesson.id,
                sort_order: index + 1,
            })),
        },
        {
            preserveScroll: true,
        }
    );
};

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

<style lang="css" scoped>
.items {
    width: 80%;
}

.add-lesson-link {
    text-decoration: none;
    color: inherit;
}
</style>