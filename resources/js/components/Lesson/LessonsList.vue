<template>
    <v-card rounded="lg" class="border" elevation="0">
        <v-list class="py-0">
            <draggable
                v-model="items"
                item-key="id"
                handle=".drag-handle"
                @end="reorder"
            >
                <template #item="{ element: lesson, index }">
                    <v-list-item class="px-5 py-3 border-b">
                        <template #prepend>
                            <div class="d-flex align-center ga-3 mr-4">
                                <v-icon
                                    class="drag-handle"
                                    color="medium-emphasis"
                                    size="20"
                                    >mdi-menu</v-icon
                                >
                                <span>{{ index + 1 }}</span>
                            </div>
                        </template>

                        <div
                            class="d-flex align-center justify-space-between ga-4"
                        >
                            <div>
                                <v-list-item-title class="font-weight-medium">{{
                                    lesson.title
                                }}</v-list-item-title>
                                <v-list-item-subtitle class="mt-1">{{
                                    lesson.description
                                }}</v-list-item-subtitle>
                            </div>
                            <v-chip
                                size="small"
                                variant="tonal"
                                :color="
                                    (
                                        lessonTypeMeta[
                                            lesson.lessonType.value
                                        ] ?? fallbackLessonMeta
                                    ).color
                                "
                            >
                                {{ lesson.lessonType.label }}
                            </v-chip>
                        </div>

                        <template #append>
                            <v-btn
                                icon="mdi-pencil-outline"
                                variant="text"
                                aria-label="Edit lesson"
                                @click="
                                    router.visit(
                                        LessonController.edit({
                                            course: course.id,
                                            lesson: lesson.id,
                                        }).url,
                                    )
                                "
                            />
                            <v-btn
                                icon="mdi-delete-outline"
                                variant="text"
                                aria-label="Delete lesson"
                                @click="ask(lesson)"
                            />
                        </template>
                    </v-list-item>
                </template>
            </draggable>

            <v-list-item v-if="items.length === 0" class="text-center py-10">
                <v-icon size="40" color="medium-emphasis"
                    >mdi-book-open-page-variant</v-icon
                >
                <v-list-item-title>No lessons yet.</v-list-item-title>
                <v-list-item-subtitle
                    >Add the first lesson to this course.</v-list-item-subtitle
                >
            </v-list-item>
        </v-list>
    </v-card>

    <AddCard
        class="mt-4"
        label="Add Lesson"
        @click="
            router.visit(LessonController.create({ course: course.id }).url)
        "
    />

    <ConfirmDialog
        v-model="open"
        title="Delete lesson"
        confirm-label="Delete"
        color="error"
        @confirm="remove"
    >
        <strong>{{ selected?.title }}</strong> will be deleted. Remaining
        lessons are renumbered. This cannot be undone.
    </ConfirmDialog>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import draggable from "vuedraggable";
import LessonController from "@/actions/App/Http/Controllers/LessonController";
import AddCard from "@/components/Common/AddCard.vue";
import ConfirmDialog from "@/components/Common/ConfirmDialog.vue";
import type { CourseDetail } from "@/types/course";
import type { Lesson } from "@/types/lesson";
import { fallbackLessonMeta, lessonTypeMeta } from "@/utils/lessonType";
import type { LessonListItem } from "@/types/lesson";

const props = defineProps<{ lessons: LessonListItem[]; course: CourseDetail }>();

const items = ref([...props.lessons]);
watch(
    () => props.lessons,
    (value) => (items.value = [...value]),
);

const open = ref(false);
const selected = ref<LessonListItem | null>(null);

function ask(lesson: LessonListItem) {
    selected.value = lesson;
    open.value = true;
}

function reorder() {
    router.put(
        LessonController.reorder({ course: props.course.id }).url,
        {
            lessons: items.value.map((lesson, index) => ({
                id: lesson.id,
                sort_order: index + 1,
            })),
        },
        {
            preserveScroll: true,
            onError: () => (items.value = [...props.lessons]),
        },
    );
}

function remove() {
    if (!selected.value) return;

    router.delete(
        LessonController.destroy({
            course: props.course.id,
            lesson: selected.value.id,
        }).url,
        {
            preserveScroll: true,
            onFinish: () => (open.value = false),
        },
    );
}
</script>
