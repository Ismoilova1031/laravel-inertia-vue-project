<template>
    <v-navigation-drawer v-model="drawer">
        <v-list>
            <v-list-item
                prepend-icon="mdi-account-circle-outline"
                :title="auth?.name ?? 'Guest'"
                :subtitle="auth?.type"
                class="border-b pa-2"
            />

            <v-list-item
                v-for="item in items"
                :key="item.title"
                link
                :title="item.title"
                :component="Link"
                :href="item.url"
                :active="isActive(item.url)"
            >
                <template #prepend
                    ><v-icon size="small">{{ item.icon }}</v-icon></template
                >
                <template v-if="item.badge" #append>
                    <v-badge :content="item.badge" color="error" inline />
                </template>
            </v-list-item>
        </v-list>

        <template #append>
            <v-list>
                <v-list-item
                    link
                    title="Logout"
                    @click="router.post(AuthController.logout().url)"
                >
                    <template #prepend
                        ><v-icon size="small">mdi-logout</v-icon></template
                    >
                </v-list-item>
            </v-list>
        </template>
    </v-navigation-drawer>
</template>

<script setup lang="ts">
import { Link, router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import AuthController from "@/actions/App/Http/Controllers/AuthController";
import CatalogController from "@/actions/App/Http/Controllers/CatalogController";
import CourseController from "@/actions/App/Http/Controllers/CourseController";
import DashboardController from "@/actions/App/Http/Controllers/DashboardController";
import LearningController from "@/actions/App/Http/Controllers/LearningController";
import NotificationController from "@/actions/App/Http/Controllers/NotificationController";
import StudentController from "@/actions/App/Http/Controllers/StudentController";
import type { AuthUser } from "@/types/auth";

interface NavItem {
    title: string;
    icon: string;
    url: string;
    badge?: number;
}

const drawer = defineModel<boolean>();

const page = usePage();
const auth = computed(() => page.props.auth as AuthUser | null);
const unread = computed(
    () => (page.props.unreadNotifications as number | null) ?? 0,
);

const items = computed<NavItem[]>(() => {
    if (auth.value?.type === "teacher") {
        return [
            {
                title: "Dashboard",
                icon: "mdi-view-dashboard",
                url: DashboardController.index().url,
            },
            {
                title: "Courses",
                icon: "mdi-book-open-page-variant",
                url: CourseController.index().url,
            },
            {
                title: "Create course",
                icon: "mdi-plus",
                url: CourseController.create().url,
            },
            {
                title: "Students",
                icon: "mdi-account-multiple-outline",
                url: StudentController.index().url,
            },
        ];
    }

    if (auth.value?.type === "student") {
        const student = auth.value.id;

        return [
            {
                title: "My courses",
                icon: "mdi-book-open-variant",
                url: LearningController.courses({ student }).url,
            },
            {
                title: "Browse courses",
                icon: "mdi-compass-outline",
                url: CatalogController.index({ student }).url,
            },
            {
                title: "Notifications",
                icon: "mdi-bell-outline",
                url: NotificationController.index({ student }).url,
                badge: unread.value,
            },
        ];
    }

    return [];
});

const isActive = (url: string) =>
    url === "/" ? page.url === "/" : page.url.startsWith(url);
</script>
