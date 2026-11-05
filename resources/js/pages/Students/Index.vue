<template>
    <v-container class="py-8">
        <Head title="Students" />

        <PageHeader title="Students" subtitle="All registered students.">
            <template #actions>
                <v-btn
                    color="primary"
                    prepend-icon="mdi-plus"
                    @click="router.visit(StudentController.create().url)"
                >
                    New student
                </v-btn>
            </template>
        </PageHeader>

        <StudentList :students="students" />
    </v-container>
</template>

<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3";
import StudentController from "@/actions/App/Http/Controllers/StudentController";
import PageHeader from "@/components/Common/PageHeader.vue";
import StudentList from "@/components/Student/StudentList.vue";
import type { Student } from "@/types/student";

defineProps<{ students: Student[] }>();
</script>
