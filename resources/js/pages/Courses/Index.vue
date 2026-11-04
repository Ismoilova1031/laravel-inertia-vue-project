<template>
    <v-container class="py-8">
        <Head title="Courses" />

        <PageHeader
            title="Study Planner"
            subtitle="Manage your courses and keep your learning organized."
        >
            <template #actions>
                <v-btn
                    color="primary"
                    prepend-icon="mdi-plus"
                    size="large"
                    @click="router.visit(CourseController.create().url)"
                >
                    Create Course
                </v-btn>
            </template>
        </PageHeader>

        <v-tabs v-model="tab" class="mb-6">
            <v-tab v-for="t in courseTabs" :key="t.value" :value="t.value">
                {{ t.label }}
                <v-chip size="x-small" variant="tonal" class="ms-2">{{
                    counts[t.value]
                }}</v-chip>
            </v-tab>
        </v-tabs>

        <v-row v-if="visible.length">
            <v-col
                v-for="course in visible"
                :key="course.id"
                cols="12"
                sm="6"
                lg="4"
                xl="3"
            >
                <CourseCard
                    :course="course"
                    @open="router.visit(CourseController.show(course.id).url)"
                    @action="open(course)"
                />
            </v-col>
        </v-row>

        <EmptyState v-else :title="emptyTitle" />

        <CourseActionDialog
            v-model="dialog"
            :course="selected"
            :loading="processing"
            @confirm="confirm"
        />

        <v-snackbar
            v-model="snackbar.show"
            :color="snackbar.color"
            location="top center"
            timeout="4000"
        >
            {{ snackbar.text }}
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import CourseController from "@/actions/App/Http/Controllers/CourseController";
import CourseActionDialog from "@/components/Course/CourseActionDialog.vue";
import CourseCard from "@/components/Course/CourseCard.vue";
import EmptyState from "@/components/Common/EmptyState.vue";
import PageHeader from "@/components/Common/PageHeader.vue";
import { useCourseActions } from "@/composables/useCourseActions";
import { useSnackbar } from "@/composables/useSnackbar";
import type { Course } from "@/types/course";
import { COURSE_STATUS, courseTabs } from "@/utils/courseStatus";

const props = defineProps<{ courses: Course[] }>();

const tab = ref<number>(COURSE_STATUS.PUBLISHED);

const { snackbar, notify } = useSnackbar();
const { dialog, processing, selected, open, confirm } =
    useCourseActions(notify);

const counts = computed(() => {
    const result: Record<number, number> = {
        [COURSE_STATUS.DRAFT]: 0,
        [COURSE_STATUS.PUBLISHED]: 0,
        [COURSE_STATUS.ARCHIVED]: 0,
    };
    for (const course of props.courses) {
        result[course.status.value]++;
    }

    return result;
});

const visible = computed(() =>
    props.courses.filter((c) => c.status.value === tab.value),
);
const emptyTitle = computed(
    () => courseTabs.find((t) => t.value === tab.value)?.empty ?? "No courses",
);
</script>
