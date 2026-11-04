<template>
    <v-container class="py-8">
        <Head :title="`Edit ${lesson.title}`" />

        <PageHeader
            title="Update Lesson"
            subtitle="Structure individual modules and attach dynamic tasks or reading assets."
            :back-url="CourseController.show(course.id).url"
            back-label="Back to course"
        />

        <LessonForm
            :form="form"
            :types="types"
            submit-label="Update Lesson"
            :submit="submit"
            :video-url="lesson.video_url"
            show-position
            :max-position="course.lessonsCount"
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
import type { Lesson } from "@/types/lesson";
import { toLessonFormData } from "@/utils/lessonFormData";

const props = defineProps<{
    course: Course & { lessonsCount: number };
    lesson: Lesson;
    types: SelectOption[];
}>();

const { form, submit } = useLessonForm(
    props.course.id,
    toLessonFormData(props.lesson),
    props.lesson.id,
    props.lesson.video_url,
);
</script>
