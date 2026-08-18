<template>
    <v-container class="py-8">
        <v-card class="mx-auto" rounded="lg" elevation="2">
            <v-card-text class="d-flex justify-space-between align-center ga-4 pa-6">
                <div class="d-flex flex-column ga-2">
                    <v-card-title class="text-h4 font-weight-bold pa-0">
                        {{ course.title }}
                    </v-card-title>

                    <p class="text-body-1 text-medium-emphasis mb-0">
                        {{ course.description }}
                    </p>
                </div>

                <div class="d-flex ga-2 flex-shrink-0">
                    <v-chip color="primary" variant="tonal">
                        {{ course.category }}
                    </v-chip>

                    <v-chip color="success" variant="tonal">
                        {{ course.status }}
                    </v-chip>
                </div>
            </v-card-text>

            <v-divider />

            <v-card-item class="d-flex justify-space-between align-center px-6 py-4">
                <div class="informations">
                    <v-icon size="small" class="me-2">mdi-account-multiple</v-icon>
                    <span class="text-body-2 text-medium-emphasis">{{ students }} students enrolled</span>

                    <v-icon size="small" class="ms-4 me-2">mdi-book</v-icon>
                    <span class="text-body-2 text-medium-emphasis">{{ lessons }} lessons</span>
                </div>
            </v-card-item>
        </v-card>

        <v-tabs v-model="tab" class="mt-6" background-color="primary" dark>
            <v-tab value="Lessons">Lessons</v-tab>
            <v-tab value="Students">Students</v-tab>
            <v-tab value="Settings">Settings</v-tab>
        </v-tabs>

        <v-divider />

        <v-tabs-window v-model="tab" class="mt-4">
            <v-tabs-window-item value="Lessons">
               <LessonsList :lessons="course.lessons" />
            </v-tabs-window-item>
            <v-tabs-window-item value="Students">
                <StudentList :students="course.students" />
            </v-tabs-window-item>
            <v-tabs-window-item value="Settings">
                <v-card flat>
                    <v-card-text>
                        <p>Settings content goes here.</p>
                    </v-card-text>
                </v-card>
            </v-tabs-window-item>
        </v-tabs-window>
    </v-container>
</template>

<script setup lang="ts">
import type { Course } from "../../types/course";
import { ref } from "vue";
import LessonsList from "../../Components/LessonsList.vue";
import StudentList from "../../Components/StudentList.vue";
const { course } = defineProps<{
    course: Course;
}>();

const lessons = course.lessons.length;
const students = course.students.length;
  const tab = ref('Lessons');
</script>
