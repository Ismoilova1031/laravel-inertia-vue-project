<template>
    <v-container class="py-8">
        <Head title="Create course" />

        <PageHeader
            title="Create Course"
            subtitle="Add a new course to your study planner."
            :back-url="CourseController.index().url"
            back-label="Courses"
        />

        <v-card max-width="900" class="mx-auto" rounded="lg" elevation="2">
            <v-card-text class="pa-6">
                <CourseForm
                    :form="form"
                    :categories="categories"
                    :statuses="statuses"
                    submit-label="Create Course"
                    @submit="submit"
                />
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import CourseController from "@/actions/App/Http/Controllers/CourseController";
import PageHeader from "@/components/Common/PageHeader.vue";
import CourseForm from "@/components/Course/CourseForm.vue";
import { useCourseForm } from "@/composables/useCourseForm";
import type { SelectOption } from "@/types/common";

defineProps<{ categories: SelectOption[]; statuses: SelectOption[] }>();

const { form, submit } = useCourseForm();
</script>
