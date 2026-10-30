<template>
    <v-navigation-drawer v-model="drawer">
        <v-list>
            <v-list-item
                prepend-icon="mdi-account-circle-outline"
                :title="auth?.name ?? 'Guest'"
                :subtitle="auth?.type"
                class="border-b pa-2"
            />

            <template v-if="auth?.type === 'teacher'">
                <v-list-item link title="Dashboard" :component="Link" :href="DashboardController.index().url">
                    <template #prepend><v-icon size="small">mdi-view-dashboard</v-icon></template>
                </v-list-item>

                <v-list-group value="Courses">
                    <template #activator="{ props }">
                        <v-list-item v-bind="props" title="Courses List">
                            <template #prepend><v-icon size="small">mdi-book-open-page-variant</v-icon></template>
                        </v-list-item>
                    </template>

                    <v-list-item link title="All Courses" class="ml-0" :component="Link" :href="CourseController.index().url">
                        <template #prepend><v-icon size="small">mdi-format-list-bulleted</v-icon></template>
                    </v-list-item>
                </v-list-group>

                <v-list-item link title="Create Course" :component="Link" :href="CourseController.create().url">
                    <template #prepend><v-icon size="small">mdi-plus</v-icon></template>
                </v-list-item>

                <v-list-item link title="Students" :component="Link" :href="StudentController.index().url">
                    <template #prepend><v-icon size="small">mdi-account-multiple-outline</v-icon></template>
                </v-list-item>
            </template>

            <template v-else-if="auth?.type === 'student'">
                <v-list-item link title="My courses" :component="Link" :href="LearningController.courses({ student: auth.id }).url">
                    <template #prepend><v-icon size="small">mdi-book-open-variant</v-icon></template>
                </v-list-item>

                <v-list-item link title="Notifications" :component="Link" :href="NotificationController.index({ student: auth.id }).url">
                    <template #prepend><v-icon size="small">mdi-bell-outline</v-icon></template>
                    <template #append>
                        <v-badge v-if="unread" :content="unread" color="error" inline />
                    </template>
                </v-list-item>
            </template>
        </v-list>

        <template #append>
            <v-list>
                <v-list-item link title="Logout" @click="logout">
                    <template #prepend><v-icon size="small">mdi-logout</v-icon></template>
                </v-list-item>
            </v-list>
        </template>
    </v-navigation-drawer>
</template>

<script setup lang="ts">
import { Link, router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import AuthController from "@/actions/App/Http/Controllers/AuthController";
import CourseController from "@/actions/App/Http/Controllers/CourseController";
import DashboardController from "@/actions/App/Http/Controllers/DashboardController";
import LearningController from "@/actions/App/Http/Controllers/LearningController";
import NotificationController from "@/actions/App/Http/Controllers/NotificationController";
import StudentController from "@/actions/App/Http/Controllers/StudentController";
import type { AuthUser } from "@/types/auth";

const drawer = defineModel<boolean>();

const page = usePage();
const auth = computed(() => page.props.auth as AuthUser | null);
const unread = computed(() => (page.props.unreadNotifications as number | null) ?? 0);

function logout() {
    router.post(AuthController.logout().url);
}
</script>

<style scoped>
:deep(.v-list-group__items .v-list-item) {
    padding-inline-start: calc(var(--indent-padding) - 8px);
}
</style>