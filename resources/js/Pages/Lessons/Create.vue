<template>
    <v-app>
        <v-container class="py-8">
            <h2 class="text-title-large font-weight-bold">
                Create New Lesson
            </h2>

            <p class="text-medium-emphasis">
                Structure individual modules and attach dynamic tasks or reading assets
            </p>

            <LessonForm
                :form="form"
                :types="types"
                submit-label="Create Lesson"
                :submit="submit"
            />
        </v-container>
    </v-app>
</template>

<script setup lang="ts">
import LessonForm from "../../components/LessonForm.vue";
import { useLessonForm } from "../../forms/lessonForm.ts";
import type { Course } from "../../types/course.ts";
import type { SelectOption } from "../../types/common.ts";
const props = defineProps<{
    course: Course;
    types: SelectOption[];
}>();

const { form, submit } = useLessonForm(props.course.id);
</script>