<template>
    <v-container>
        <h1 class="mb-6">{{ course.title }}</h1>

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
}
defineProps<{
    course: Course;
}>();
</script>