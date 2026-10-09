<template>
    <v-container>

        <Head title="Student tahrirlash" />

        <v-card variant="text" class="mb-4 pa-2 d-flex flex-row gap-2 align-center justify-space-between">
            <div>
                <v-card-title>Studentni tahrirlash</v-card-title>
                <v-card-subtitle>Student ma'lumotlarini yangilash</v-card-subtitle>
            </div>
            <v-btn variant="tonal" color="primary" class="mb-4" :href="index().url">
                <v-icon start>mdi-arrow-left</v-icon>
                Ro'yxatga qaytish
            </v-btn>
        </v-card>
        <StudentForm :form="form" :submit="submit" submit-label="Yangilash" />
    </v-container>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import StudentForm from '../../components/StudentForm.vue';
import { index } from '../../actions/App/Http/Controllers/StudentController.js';
import { useStudentForm } from '../../forms/studentForm';
import type { StudentRow } from '../../types/student';

const props = defineProps<{
    student: StudentRow;
}>();

const { form, submit } = useStudentForm(
    {
        name: props.student.name,
        surname: props.student.surname,
        email: props.student.email,
        username: props.student.username,
        password: '',
        password_confirmation: '',
    },
    props.student.id,
);
</script>
