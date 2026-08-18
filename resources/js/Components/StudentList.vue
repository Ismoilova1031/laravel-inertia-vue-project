<template>
    <v-card rounded="lg" elevation="1">
        <v-list lines="two" class="py-0">
            <v-list-item
                v-for="student in students"
                :key="student.id"
                class="px-5 py-3"
            >
                <template #prepend>
                    <v-avatar
                        color="primary"
                        variant="tonal"
                        size="42"
                    >
                        {{ student.name.charAt(0) }}
                    </v-avatar>
                </template>

                <v-list-item-title class="font-weight-medium">
                    {{ student.name }} {{ student.surname }}
                </v-list-item-title>

                <v-list-item-subtitle class="mt-1">
                    {{ student.email }}
                </v-list-item-subtitle>

                <template #append>
                    <div class="d-flex align-center ga-3">
                        <v-chip
                            size="small"
                            variant="tonal"
                            color="primary"
                        >
                            @{{ student.username }}
                        </v-chip>

                        <v-btn
                            icon
                            variant="text"
                            size="small"
                        >
                            <v-icon>
                                mdi-dots-vertical
                            </v-icon>
                        </v-btn>
                    </div>
                </template>
            </v-list-item>

            <v-list-item
                v-if="students.length === 0"
                class="text-center py-10"
            >
                <template #prepend>
                    <v-icon
                        size="40"
                        color="medium-emphasis"
                    >
                        mdi-account-group-outline
                    </v-icon>
                </template>

                <v-list-item-title>
                    No students yet
                </v-list-item-title>

                <v-list-item-subtitle class="mt-1">
                    No students are enrolled in this course.
                </v-list-item-subtitle>
            </v-list-item>
        </v-list>
    </v-card>
</template>

<script setup lang="ts">
import type { Student } from "../types/student";

defineProps<{
    students: Student[];
}>();
</script>