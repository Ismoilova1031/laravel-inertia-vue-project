<template>
    <v-container class="py-8">
        <Head :title="`Edit ${student.name}`" />

        <PageHeader
            title="Edit student"
            subtitle="Update the student's details. Leave the password empty to keep it."
            :back-url="StudentController.index().url"
            back-label="Back to students"
        />

        <StudentForm :form="form" :submit="submit" submit-label="Update" />
    </v-container>
</template>

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import StudentController from "@/actions/App/Http/Controllers/StudentController";
import PageHeader from "@/components/Common/PageHeader.vue";
import StudentForm from "@/components/Student/StudentForm.vue";
import { useStudentForm } from "@/composables/useStudentForm";
import type { Student } from "@/types/student";

const props = defineProps<{ student: Student }>();

const { form, submit } = useStudentForm(
    undefined,
    {
        name: props.student.name,
        surname: props.student.surname,
        email: props.student.email,
        username: props.student.username,
        password: "",
        password_confirmation: "",
    },
    props.student.id,
);
</script>
