<template>
    <v-container class="py-8">
        <Head title="Dashboard" />
        <PageHeader title="Dashboard" subtitle="Where would you like to go?" />

        <v-row>
            <v-col
                v-for="item in items"
                :key="item.title"
                cols="12"
                sm="6"
                md="4"
            >
                <v-card
                    rounded="lg"
                    class="pa-2"
                    elevation="2"
                    height="100%"
                    link
                    @click="router.visit(item.url)"
                >
                    <v-card-item>
                        <template #prepend>
                            <v-avatar color="primary" variant="tonal"
                                ><v-icon :icon="item.icon"
                            /></v-avatar>
                        </template>
                        <v-card-title class="font-weight-bold">{{
                            item.title
                        }}</v-card-title>
                        <v-card-subtitle class="text-wrap">{{
                            item.text
                        }}</v-card-subtitle>
                    </v-card-item>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3";
import CourseController from "@/actions/App/Http/Controllers/CourseController";
import StudentController from "@/actions/App/Http/Controllers/StudentController";
import PageHeader from "@/components/Common/PageHeader.vue";

const items = [
    {
        title: "Courses",
        text: "Manage drafts, published and archived courses.",
        icon: "mdi-book-open-page-variant",
        url: CourseController.index().url,
    },
    {
        title: "Create course",
        text: "Start a new course and add lessons.",
        icon: "mdi-plus-box-outline",
        url: CourseController.create().url,
    },
    {
        title: "Students",
        text: "Add students, enroll them and view their progress.",
        icon: "mdi-account-multiple-outline",
        url: StudentController.index().url,
    },
];
</script>
