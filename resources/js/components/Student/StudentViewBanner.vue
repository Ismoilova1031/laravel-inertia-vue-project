<template>
    <v-alert
        v-if="isTeacher"
        type="info"
        variant="tonal"
        density="compact"
        class="mb-6"
        icon="mdi-account-eye-outline"
    >
        <div class="d-flex align-center flex-wrap ga-2">
            <span
                >Viewing as <strong>{{ student.fullName }}</strong></span
            >
            <v-spacer />
            <v-btn
                v-for="link in links"
                :key="link.label"
                size="small"
                variant="text"
                :prepend-icon="link.icon"
                @click="router.visit(link.url)"
            >
                {{ link.label }}
                <v-badge
                    v-if="link.badge"
                    :content="link.badge"
                    color="error"
                    inline
                    class="ms-1"
                />
            </v-btn>
        </div>
    </v-alert>
</template>

<script setup lang="ts">
import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import CatalogController from "@/actions/App/Http/Controllers/CatalogController";
import LearningController from "@/actions/App/Http/Controllers/LearningController";
import NotificationController from "@/actions/App/Http/Controllers/NotificationController";
import StudentController from "@/actions/App/Http/Controllers/StudentController";
import type { AuthUser } from "@/types/auth";
import type { StudentSummary } from "@/types/student";

const props = defineProps<{ student: StudentSummary }>();

const page = usePage();
const isTeacher = computed(
    () => (page.props.auth as AuthUser | null)?.type === "teacher",
);
const unread = computed(
    () => (page.props.unreadNotifications as number | null) ?? 0,
);

const links = computed(() => [
    {
        label: "My courses",
        icon: "mdi-book-open-variant",
        url: LearningController.courses({ student: props.student.id }).url,
    },
    {
        label: "Catalog",
        icon: "mdi-compass-outline",
        url: CatalogController.index({ student: props.student.id }).url,
    },
    {
        label: "Notifications",
        icon: "mdi-bell-outline",
        url: NotificationController.index({ student: props.student.id }).url,
        badge: unread.value,
    },
    {
        label: "Back to students",
        icon: "mdi-arrow-left",
        url: StudentController.index().url,
    },
]);
</script>
