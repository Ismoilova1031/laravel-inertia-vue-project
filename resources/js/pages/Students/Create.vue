<template>
    <v-container>
        <Head title="Student qo'shish" />

        <v-card
            variant="text"
            class="mb-4 pa-2 d-flex flex-row gap-2 align-center justify-space-between"
        >
            <div>
                <v-card-title>Yangi student qo'shish</v-card-title>
                <v-card-subtitle
                    >Yangi student qo'shish uchun quyidagi formani
                    toldiring.</v-card-subtitle
                >
            </div>
            <v-btn
                variant="tonal"
                color="primary"
                class="mb-4"
                :href="index().url"
            >
                <v-icon start>mdi-arrow-left</v-icon>
                Ro'yxatga qaytish
            </v-btn>
        </v-card>
        <StudentForm :form="form" :submit="submit" submit-label="Qo'shish" />
    </v-container>
</template>

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import StudentForm from "@/components/Student/StudentForm.vue";
import { index } from "@/actions/App/Http/Controllers/StudentController.js";
import { useStudentForm } from "@/forms/studentForm";

const props = defineProps<{
    course_id?: number;
}>();

const { form, submit } = useStudentForm(props.course_id, {
    name: "",
    surname: "",
    email: "",
    username: "",
    password: "",
    password_confirmation: "",
});
</script>
