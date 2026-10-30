<template>
    <v-alert type="info" variant="tonal" density="compact" class="mb-6" icon="mdi-account-eye-outline">
        <div class="d-flex align-center justify-space-between ga-4">
            <span>
                Viewing as <strong>{{ student.fullName }}</strong>
            </span>
            <v-btn size="small" variant="text" prepend-icon="mdi-arrow-left"
                @click="router.visit(StudentController.index().url)">
                Back to students
            </v-btn>
            <v-btn size="small" variant="text" prepend-icon="mdi-book-open-variant"
                @click="router.visit(LearningController.courses({ student: student.id }).url)">My courses</v-btn>
            <v-btn size="small" variant="text" prepend-icon="mdi-bell-outline"
                @click="router.visit(NotificationController.index({ student: student.id }).url)">
                Notifications
                <v-badge v-if="unread" :content="unread" color="error" inline class="ms-1" />
            </v-btn>
        </div>
    </v-alert>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import StudentController from "@/actions/App/Http/Controllers/StudentController";
import type { StudentSummary } from "@/types/student";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import LearningController from "@/actions/App/Http/Controllers/LearningController";
import NotificationController from "@/actions/App/Http/Controllers/NotificationController";

const unread = computed(() => (usePage().props.unreadNotifications as number | null) ?? 0);
defineProps<{ student: StudentSummary }>();
</script>