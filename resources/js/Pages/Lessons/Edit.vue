<template>
    <v-app>
        <v-container class="py-8">
            <h2 class="text-title-large font-weight-bold">
                Update Lesson
            </h2>

            <p class="text-medium-emphasis">
                Structure individual modules and attach dynamic tasks or reading assets
            </p>

            <LessonForm
                :form="form"
                :types="types"
                submit-label="Update Lesson"
                :submit="submit"
                :videoUrl="lesson.video_src"
            />
        </v-container>
    </v-app>
</template>
<script setup lang="ts">
import LessonForm from "../../components/LessonForm.vue";
import { useLessonForm } from "../../forms/lessonForm";
import type { Course } from "../../types/course";
import type { Lesson } from "../../types/lesson";
import type { LessonType } from "../../types/lesson-types";
import type { SelectOption } from "../../types/common";
const props = defineProps<{
    course: Course;
    lesson: Lesson;
    types: SelectOption[];
}>();

const { form, submit } = useLessonForm(props.course.id, {
    title: props.lesson.title,
    description: props.lesson.description,
    sort_order: props.lesson.sort_order,
    lesson_type: props.lesson.lesson_type as unknown as LessonType,
    video: null,
    lesson_content: props.lesson.lesson_content ?? "",
    task: props.lesson.task,
}, props.lesson.id, props.lesson.video_src);

</script>