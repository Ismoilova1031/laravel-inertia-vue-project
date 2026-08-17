<template>
    <v-container>
        <h1 class="mb-6">{{ course.title }}</h1>
        <v-card-title>{{ course.category }}</v-card-title>
        <v-card-title>{{ course.status }}</v-card-title>

        <v-card-text>
            {{ course.description }}
        </v-card-text>
    </v-container>
</template>
<script setup lang="ts">
interface Course {
    id: number;
    title: string;
    description: string;
    category: {
        value: number;
        label: string;
    };
    status: {
        value: number;
        label: string;
    }
}
defineProps<{
    course: Course;
}>();
</script>