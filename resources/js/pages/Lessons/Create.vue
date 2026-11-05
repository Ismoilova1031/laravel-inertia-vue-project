<template>
    <v-container class="py-8">
        <Head title="New lesson" />

        <PageHeader
            title="Create New Lesson"
            subtitle="Structure individual modules and attach dynamic tasks or reading assets."
            :back-url="CourseController.show(course.id).url"
            back-label="Back to course"
        />

        <LessonForm
            :form="form"
            :types="types"
            submit-label="Create Lesson"
            @submit="submit"
        />
    </v-container>
</template>

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import CourseController from "@/actions/App/Http/Controllers/CourseController";
import PageHeader from "@/components/Common/PageHeader.vue";
import LessonForm from "@/components/Lesson/LessonForm.vue";
import { useLessonForm } from "@/composables/useLessonForm";
import type { SelectOption } from "@/types/common";
import type { Course } from "@/types/course";

const props = defineProps<{ course: Course; types: SelectOption[] }>();

const { form, submit } = useLessonForm(props.course.id);
</script>
