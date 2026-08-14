<template>
    <v-app>
        <v-main>
            <v-container>
                <h1 class="mb-6">Study Planner</h1>

                <v-row>
                    <v-col
                        v-for="course in courses"
                        :key="course.id"
                        cols="12"
                        md="6"
                        lg="4"
                    >
                        <v-card>
                            <v-card-title>
                                {{ course.title }}
                            </v-card-title>

                            <v-card-text>
                                {{ course.description }}
                            </v-card-text>

                            <v-card-actions>
                                <v-btn>
                                    View Course
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
<script setup lang="ts">
interface Course {
    id: number;
    title: string;
    description: string;
}

defineProps<{
    courses: Course[];
}>();
</script>