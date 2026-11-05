<template>
    <v-container class="py-8">
        <Head title="Notifications" />
        <StudentViewBanner :student="student" />

        <PageHeader title="Notifications">
            <template #actions>
                <v-btn
                    variant="text"
                    prepend-icon="mdi-check-all"
                    :disabled="!hasUnread"
                    @click="readAll"
                >
                    Mark all as read
                </v-btn>
            </template>
        </PageHeader>

        <v-card
            v-if="notifications.length"
            rounded="lg"
            class="border"
            elevation="0"
        >
            <v-list class="py-0" lines="two">
                <template v-for="(n, i) in notifications" :key="n.id">
                    <v-divider v-if="i > 0" />
                    <v-list-item
                        class="px-5 py-3"
                        :class="{ unread: !n.isRead }"
                        @click="open(n)"
                    >
                        <template #prepend>
                            <v-avatar
                                :color="meta(n.type).color"
                                variant="tonal"
                                class="mr-4"
                            >
                                <v-icon :icon="meta(n.type).icon" />
                            </v-avatar>
                        </template>
                        <v-list-item-title class="font-weight-medium">{{
                            n.title
                        }}</v-list-item-title>
                        <v-list-item-subtitle
                            v-if="n.description"
                            class="mt-1"
                            >{{ n.description }}</v-list-item-subtitle
                        >
                        <template #append>
                            <span class="text-caption text-medium-emphasis">{{
                                n.createdAt
                            }}</span>
                        </template>
                    </v-list-item>
                </template>
            </v-list>
        </v-card>

        <EmptyState
            v-else
            icon="mdi-bell-outline"
            title="No notifications yet"
        />
    </v-container>
</template>

<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3";
import { computed } from "vue";
import LearningController from "@/actions/App/Http/Controllers/LearningController";
import NotificationController from "@/actions/App/Http/Controllers/NotificationController";
import EmptyState from "@/components/Common/EmptyState.vue";
import PageHeader from "@/components/Common/PageHeader.vue";
import StudentViewBanner from "@/components/Student/StudentViewBanner.vue";
import type { LearnNotification } from "@/types/learning";
import type { StudentSummary } from "@/types/student";
import {
    fallbackNotificationMeta,
    notificationMeta,
} from "@/utils/notificationType";

const props = defineProps<{
    student: StudentSummary;
    notifications: LearnNotification[];
}>();

const hasUnread = computed(() => props.notifications.some((n) => !n.isRead));
const meta = (type: number) =>
    notificationMeta[type] ?? fallbackNotificationMeta;

function open(n: LearnNotification) {
    if (!n.isRead) {
        router.post(
            NotificationController.read({
                student: props.student.id,
                notification: n.id,
            }).url,
            {},
            { preserveScroll: true },
        );
    }

    if (n.courseId !== null) {
        router.visit(
            LearningController.course({
                student: props.student.id,
                course: n.courseId,
            }).url,
        );
    }
}

function readAll() {
    router.post(
        NotificationController.readAll({ student: props.student.id }).url,
        {},
        { preserveScroll: true },
    );
}
</script>

<style scoped>
.unread {
    background: rgba(var(--v-theme-primary), 0.06);
}
</style>
