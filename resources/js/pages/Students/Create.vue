<template>
    <v-container class="py-8">
        <Head title="New student" />

        <PageHeader
            title="New student"
            :subtitle="
                course_id != null
                    ? 'The student will be enrolled in this course.'
                    : 'Fill in the form to add a student.'
            "
            :back-url="backUrl"
            :back-label="
                course_id != null ? 'Back to course' : 'Back to students'
            "
        />

        <StudentForm :form="form" :submit="submit" submit-label="Create" />
    </v-container>
</template>

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import CourseController from "@/actions/App/Http/Controllers/CourseController";
import StudentController from "@/actions/App/Http/Controllers/StudentController";
import PageHeader from "@/components/Common/PageHeader.vue";
import StudentForm from "@/components/Student/StudentForm.vue";
import { useStudentForm } from "@/composables/useStudentForm";

const props = defineProps<{ course_id?: number | null }>();

const backUrl = computed(() =>
    props.course_id != null
        ? CourseController.show(props.course_id).url
        : StudentController.index().url,
);

const { form, submit } = useStudentForm(props.course_id ?? undefined);
</script>
