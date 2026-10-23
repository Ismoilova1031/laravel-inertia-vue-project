<template>
    <v-alert
        type="info"
        variant="tonal"
        density="compact"
        class="mb-6"
        icon="mdi-account-eye-outline"
    >
        <div class="d-flex align-center justify-space-between ga-4">
            <span>
                Viewing as <strong>{{ student.fullName }}</strong>
            </span>
            <v-btn
                size="small"
                variant="text"
                prepend-icon="mdi-arrow-left"
                @click="router.visit(StudentController.index().url)"
            >
                Back to students
            </v-btn>
        </div>
    </v-alert>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import StudentController from "../actions/App/Http/Controllers/StudentController";
import type { LearnStudent } from "../types/learning";

defineProps<{ student: LearnStudent }>();
</script>