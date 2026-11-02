<template>
    <v-container class="py-8">
        <StudentViewBanner :student="student" />

        <div class="mb-6">
            <h1 class="text-h4 font-weight-bold">Browse courses</h1>
            <p class="text-body-1 text-medium-emphasis mt-1">Like what interests you, save what you'll study later.</p>
        </div>

        <div class="d-flex align-center flex-wrap ga-4 mb-4">
            <v-tabs v-model="tab" class="flex-grow-1">
                <v-tab v-for="t in tabs" :key="t.value" :value="t.value">
                    {{ t.label }}
                    <v-chip size="x-small" variant="tonal" class="ms-2">{{ counts[t.value] }}</v-chip>
                </v-tab>
            </v-tabs>

            <v-text-field
                v-model="search"
                label="Search"
                variant="outlined"
                density="compact"
                prepend-inner-icon="mdi-magnify"
                clearable
                hide-details
                style="max-width: 280px"
            />
        </div>

        <v-row v-if="visible.length">
            <v-col v-for="course in visible" :key="course.id" cols="12" sm="6" lg="4" xl="3">
                <CatalogCourseCard
                    :course="course"
                    @toggle="(kind, enabled) => toggle(course, kind, enabled)"
                    @open="openCourse(course)"
                />
            </v-col>
        </v-row>

        <v-card v-else class="pa-8 text-center" variant="outlined" rounded="lg">
            <v-icon icon="mdi-book-search-outline" size="64" class="mb-4" />
            <h2 class="text-h5">{{ emptyText }}</h2>
        </v-card>

        <v-snackbar v-model="snackbar.show" color="error" location="top center" timeout="4000">
            {{ snackbar.text }}
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { computed, reactive, ref, watch } from "vue";
import CatalogCourseCard from "@/components/Catalog/CatalogCourseCard.vue";
import StudentViewBanner from "@/components/Student/StudentViewBanner.vue";
import CourseInterestController from "@/actions/App/Http/Controllers/CourseInterestController";
import LearningController from "@/actions/App/Http/Controllers/LearningController";
import type { CatalogCourse, InterestKind } from "@/types/catalog";
import type { StudentSummary } from "@/types/student";

const props = defineProps<{ student: StudentSummary; courses: CatalogCourse[] }>();

type Tab = "all" | "liked" | "planned";

const tabs: { value: Tab; label: string }[] = [
    { value: "all", label: "All" },
    { value: "liked", label: "Liked" },
    { value: "planned", label: "Saved for later" },
];

const tab = ref<Tab>("all");
const search = ref("");
const snackbar = reactive({ show: false, text: "" });

const items = ref<CatalogCourse[]>(props.courses.map((c) => ({ ...c })));
watch(
    () => props.courses,
    (value) => (items.value = value.map((c) => ({ ...c }))),
);

const counts = computed<Record<Tab, number>>(() => ({
    all: items.value.length,
    liked: items.value.filter((c) => c.liked).length,
    planned: items.value.filter((c) => c.planned).length,
}));

const visible = computed(() => {
    const q = (search.value ?? "").trim().toLowerCase();

    return items.value.filter((c) => {
        if (tab.value === "liked" && !c.liked) return false;
        if (tab.value === "planned" && !c.planned) return false;

        return q === "" || c.title.toLowerCase().includes(q) || c.category.toLowerCase().includes(q);
    });
});

const emptyText = computed(() => {
    if (search.value) return "Nothing matches your search";
    if (tab.value === "liked") return "You haven't liked any course yet";
    if (tab.value === "planned") return "No saved courses yet";

    return "No published courses yet";
});

function toggle(course: CatalogCourse, kind: InterestKind, enabled: boolean) {
    const key = kind === "like" ? "liked" : "planned";
    course[key] = enabled;

    router.post(
        CourseInterestController.store({ student: props.student.id, course: course.id }).url,
        { type: kind, enabled },
        {
            preserveScroll: true,
            preserveState: true,
            onError: (errors) => {
                course[key] = !enabled;
                snackbar.text = Object.values(errors)[0] ?? "Something went wrong";
                snackbar.show = true;
            },
        },
    );
}

function openCourse(course: CatalogCourse) {
    router.visit(LearningController.course({ student: props.student.id, course: course.id }).url);
}
</script>