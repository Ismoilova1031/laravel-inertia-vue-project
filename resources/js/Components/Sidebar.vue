<template>
    <v-navigation-drawer v-model="drawer">
        <v-list>
            <v-list-item prepend-avatar="https://radhakrishnaagriculture.in/cdn/shop/files/tulippink..jpg"
                title="Soliha Ismoilova" subtitle="teacher" class="border-b pa-2"></v-list-item>

            <v-list-item link title="Dashboard"
                :component="Link"
                :href="DashboardController.index().url">
                    <template #prepend>
                        <v-icon size="small">mdi-view-dashboard</v-icon>
                    </template>
                </v-list-item>

            <v-list-group value="Courses">
                <template #activator="{ props }">
                    <v-list-item v-bind="props" title="Courses List">
                        <template #prepend>
                            <v-icon size="small">mdi-book-open-page-variant</v-icon>
                        </template>
                    </v-list-item>
                </template>

                <v-list-item link title="All Courses" class="ml-0">
                    <template #prepend>
                        <v-icon size="small">mdi-format-list-bulleted</v-icon>
                    </template>
                </v-list-item>
                <v-list-item link title="Liked Courses">
                    <template #prepend>
                        <v-icon size="small">mdi-heart</v-icon>
                    </template>
                </v-list-item>
            </v-list-group>

            <v-list-item link title="Create Course" :component="Link" :href="CourseController.create().url">
                <template #prepend>
                    <v-icon size="small">mdi-plus</v-icon>
                </template>
               </v-list-item>
            <v-list-item link title="Notifications">
                <template #prepend>
                    <v-icon size="small">mdi-bell</v-icon>
                </template>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import DashboardController from "../actions/App/Http/Controllers/DashboardController";
import CourseController from "../actions/App/Http/Controllers/CourseController";

const drawer = defineModel<boolean>()
</script>
<style scoped>
    :deep(.v-list-group__items .v-list-item) {
        padding-inline-start: calc(var(--indent-padding) - 8px);
    }
</style>