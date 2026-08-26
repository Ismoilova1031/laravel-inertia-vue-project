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
                                    {{ lesson.sortOrder }}
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

                            <v-chip size="small" variant="tonal" :color="lessonTypeColor(lesson.lessonType.value)">
                                {{ lesson.lessonType.label }}
                            </v-chip>
                        </div>

                        <template #append>
                            <div class="d-flex align-center ga-3">
                                <Link :href="LessonController.edit({
                                    course: course.id,
                                    lesson: lesson.id,
                                    
                                }).url" class="update-lesson-link">
                                    <v-btn icon="mdi-pencil-outline" variant="text" size="medium" />
                                </Link>
                                <v-btn icon="mdi-delete-outline" variant="text" size="medium"
                                    @click="openDeleteDialog(lesson)" />
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
    <v-dialog v-model="deleteDialog" max-width="500">
        <v-card>
            <v-card-title class="text-h6 font-weight-bold">
                Delete Lesson
            </v-card-title>

            <v-card-text>
                Are you sure you want to delete
                <span class="font-weight-bold">
                    {{ selectedLesson?.title }}
                </span>
                ? <br>
                This action cannot be undone.
            </v-card-text>

            <v-card-actions>
                <v-spacer />

                <v-btn color="primary" variant="text" @click="closeDeleteDialog">
                    Cancel
                </v-btn>

                <v-btn color="error" variant="text" @click="confirmDelete">
                    Delete
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import type { Lesson } from "../types/lesson";
import LessonController from "../actions/App/Http/Controllers/LessonController";
import type { CourseDetail } from "../types/course";
import draggable from "vuedraggable";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    lessons: Lesson[];
    course: CourseDetail;
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

const deleteDialog = ref(false);
const selectedLesson = ref<Lesson | null>(null);

function openDeleteDialog(lesson: Lesson) {
    selectedLesson.value = lesson;
    deleteDialog.value = true;
}

function closeDeleteDialog() {
    deleteDialog.value = false;
    selectedLesson.value = null;
}

function confirmDelete() {
    if (!selectedLesson.value) {
        return;
    }

    const lessonId = selectedLesson.value.id;

    router.delete(
        LessonController.destroy({
            course: props.course.id,
            lesson: selectedLesson.value.id,
        }).url,
        {
            preserveScroll: true,

            onSuccess: () => {
                lessons.value = lessons.value
                    .filter(lesson => lesson.id !== lessonId)
                    .map((lesson, index) => ({
                        ...lesson,
                        sortOrder: index + 1,
                    }));
            },

            onFinish: closeDeleteDialog,
        }
    );
}

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