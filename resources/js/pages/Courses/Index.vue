<template>
    <v-container class="py-8">
        <div class="d-flex align-center justify-space-between mb-6">
            <div>
                <h1 class="text-h3 font-weight-bold">Study Planner</h1>
                <p class="text-body-1 text-medium-emphasis mt-2">
                    Manage your courses and keep your learning organized.
                </p>
            </div>

            <v-btn color="primary" prepend-icon="mdi-plus" size="large"
                @click="router.visit(CourseController.create().url)">
                Create Course
            </v-btn>
        </div>

        <v-tabs v-model="tab" class="mb-6">
            <v-tab v-for="t in tabs" :key="t.value" :value="t.value">
                {{ t.label }}
                <v-chip size="x-small" variant="tonal" class="ms-2">
                    {{ counts[t.value] }}
                </v-chip>
            </v-tab>
        </v-tabs>

        <v-row v-if="visibleCourses.length">
            <v-col v-for="course in visibleCourses" :key="course.id" cols="12" sm="6" lg="4" xl="3">
                <v-hover v-slot="{ isHovering, props }">
                    <v-card v-bind="props" height="100%" rounded="lg" :elevation="isHovering ? 6 : 2"
                        :class="{ 'archived-course': course.status.value === 2 }">
                        <v-card-item>
                            <v-card-title class="text-h6 font-weight-bold">
                                {{ course.title }}
                            </v-card-title>
                            <v-card-subtitle class="mt-1"> Course #{{ course.id }} </v-card-subtitle>
                        </v-card-item>

                        <v-card-text class="pa-6">
                            <div class="d-flex align-center flex-wrap ga-2 mb-4">
                                <v-chip color="primary" size="small" variant="tonal">
                                    {{ course.category }}
                                </v-chip>
                                <v-chip :color="statusMeta[course.status.value].color" size="small" variant="tonal">
                                    {{ course.status.label }}
                                </v-chip>

                                <v-spacer />

                                <v-btn :icon="actionMeta[course.status.value].icon"
                                    :color="actionMeta[course.status.value].color"
                                    :aria-label="actionMeta[course.status.value].title" variant="text" size="small"
                                    @click="openDialog(course)" />
                            </div>

                            <p class="text-body-2 text-medium-emphasis">
                                {{ course.description }}
                            </p>
                        </v-card-text>

                        <v-card-actions class="pa-4">
                            <v-btn color="primary" variant="outlined" block
                                @click="router.visit(CourseController.show(course.id).url)">
                                View Course
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-hover>
            </v-col>
        </v-row>

        <v-card v-else class="pa-8 text-center" variant="outlined" rounded="lg">
            <v-icon icon="mdi-book-open-outline" size="64" class="mb-4" />
            <h2 class="text-h5 mb-2">{{ emptyText[tab] }}</h2>
        </v-card>

        <v-dialog v-model="dialog" max-width="500">
            <v-card v-if="selectedCourse" rounded="lg">
                <v-card-title class="text-h6">
                    {{ actionMeta[selectedCourse.status.value].title }}
                </v-card-title>

                <v-card-text>
                    <span class="font-weight-bold">"{{ selectedCourse.title }}"</span>
                    <br />
                    {{ actionMeta[selectedCourse.status.value].message }}
                </v-card-text>

                <v-card-actions class="px-6 py-4">
                    <v-spacer />
                    <v-btn variant="text" :disabled="processing" @click="closeDialog">Cancel</v-btn>
                    <v-btn :color="actionMeta[selectedCourse.status.value].color" variant="flat" :loading="processing"
                        @click="confirmAction">
                        {{ actionMeta[selectedCourse.status.value].confirm }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar.show" :color="snackbar.color" location="top center" timeout="4000">
            {{ snackbar.text }}
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { computed, reactive, ref } from "vue";
import type { Course } from "../../types/course";
import CourseController from "../../actions/App/Http/Controllers/CourseController";

const props = defineProps<{
    courses: Course[];
}>();

const DRAFT = 0;
const PUBLISHED = 1;
const ARCHIVED = 2;

const tabs = [
    { value: DRAFT, label: "Draft" },
    { value: PUBLISHED, label: "Published" },
    { value: ARCHIVED, label: "Archived" },
];

const tab = ref(PUBLISHED);

const emptyText: Record<number, string> = {
    [DRAFT]: "No draft courses",
    [PUBLISHED]: "No published courses",
    [ARCHIVED]: "No archived courses",
};

const statusMeta: Record<number, { color: string }> = {
    [DRAFT]: { color: "grey" },
    [PUBLISHED]: { color: "success" },
    [ARCHIVED]: { color: "warning" },
};

const actionMeta: Record<
    number,
    { icon: string; color: string; title: string; message: string; confirm: string; target: number | null }
> = {
    [DRAFT]: {
        icon: "mdi-delete",
        color: "error",
        title: "Delete Course",
        message: "This action cannot be undone.",
        confirm: "Delete",
        target: null,
    },
    [PUBLISHED]: {
        icon: "mdi-archive-outline",
        color: "warning",
        title: "Archive Course",
        message: "New students will not be able to enroll. Existing results are kept.",
        confirm: "Archive",
        target: ARCHIVED,
    },
    [ARCHIVED]: {
        icon: "mdi-publish",
        color: "success",
        title: "Republish Course",
        message: "The course will become available for enrollment again.",
        confirm: "Republish",
        target: PUBLISHED,
    },
};

const counts = computed(() => {
    const result: Record<number, number> = { [DRAFT]: 0, [PUBLISHED]: 0, [ARCHIVED]: 0 };
    for (const c of props.courses) {
        result[c.status.value]++;
    }
    return result;
});
const visibleCourses = computed(() => props.courses.filter((c) => c.status.value === tab.value));

const dialog = ref(false);
const processing = ref(false);
const selectedCourse = ref<Course | null>(null);
const snackbar = reactive({ show: false, text: "", color: "success" });

function notify(text: string, color: "success" | "error") {
    snackbar.text = text;
    snackbar.color = color;
    snackbar.show = true;
}

function openDialog(course: Course) {
    selectedCourse.value = course;
    dialog.value = true;
}

function closeDialog() {
    dialog.value = false;
}

function confirmAction() {
    const course = selectedCourse.value;
    if (!course || processing.value) return;

    const meta = actionMeta[course.status.value];
    processing.value = true;

    const options = {
        preserveScroll: true,
        onSuccess: () => notify(`${meta.confirm}: done`, "success"),
        onError: (errors: Record<string, string>) => notify(Object.values(errors)[0] ?? "Something went wrong", "error"),
        onFinish: () => {
            processing.value = false;
            closeDialog();
        },
    };

    if (meta.target === null) {
        router.delete(CourseController.destroy(course.id).url, options);
    } else {
        router.patch(CourseController.changeStatus(course.id).url, { status: meta.target }, options);
    }
}
</script>

<style scoped>
.archived-course {
    opacity: 0.7;
}
</style>