<template>
    <v-container class="py-8">
        <StudentViewBanner :student="student" />

        <div class="d-flex align-center justify-space-between mb-6">
            <h1 class="text-h4 font-weight-bold">Notifications</h1>
            <v-btn variant="text" prepend-icon="mdi-check-all" :disabled="!hasUnread" @click="readAll">
                Mark all as read
            </v-btn>
        </div>

        <v-card rounded="lg" class="border" elevation="0">
            <v-list class="py-0" lines="two">
                <template v-for="(n, i) in notifications" :key="n.id">
                    <v-divider v-if="i > 0" />
                    <v-list-item class="px-5 py-3" :class="{ unread: !n.isRead }" @click="open(n)">
                        <template #prepend>
                            <v-avatar :color="meta(n.type).color" variant="tonal" class="mr-4">
                                <v-icon :icon="meta(n.type).icon" />
                            </v-avatar>
                        </template>
                        <v-list-item-title class="font-weight-medium">{{ n.title }}</v-list-item-title>
                        <v-list-item-subtitle v-if="n.description" class="mt-1">{{ n.description }}</v-list-item-subtitle>
                        <template #append>
                            <span class="text-caption text-medium-emphasis">{{ n.createdAt }}</span>
                        </template>
                    </v-list-item>
                </template>

                <v-list-item v-if="notifications.length === 0" class="text-center py-10">
                    <v-list-item-title>No notifications yet.</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-card>
    </v-container>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { computed } from "vue";
import StudentViewBanner from "../../components/Student/StudentViewBanner.vue";
import LearningController from "../../actions/App/Http/Controllers/LearningController";
import NotificationController from "../../actions/App/Http/Controllers/NotificationController";
import type { LearnNotification } from "../../types/learning";
import type { StudentSummary } from "../../types/student";
const props = defineProps<{ student: StudentSummary; notifications: LearnNotification[] }>();

const hasUnread = computed(() => props.notifications.some((n) => !n.isRead));

const types: Record<number, { icon: string; color: string }> = {
    1: { icon: "mdi-certificate-outline", color: "success" },
    2: { icon: "mdi-file-check-outline", color: "success" },
    3: { icon: "mdi-file-alert-outline", color: "error" },
    4: { icon: "mdi-pencil-check-outline", color: "primary" },
};
const meta = (type: number) => types[type] ?? { icon: "mdi-bell-outline", color: "grey" };

function open(n: LearnNotification) {
    if (!n.isRead) {
        router.post(NotificationController.read({ student: props.student.id, notification: n.id }).url, {}, { preserveScroll: true });
    }
    if (n.courseId !== null) {
        router.visit(LearningController.course({ student: props.student.id, course: n.courseId }).url);
    }
}

function readAll() {
    router.post(NotificationController.readAll({ student: props.student.id }).url, {}, { preserveScroll: true });
}
</script>

<style scoped>
.unread {
    background: rgba(var(--v-theme-primary), 0.06);
}
</style>