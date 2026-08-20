<template>
    <v-card class="pa-5 ma-0 md:ma-5 border">
        <v-card-title class="text-title-medium pa-2">
            <v-icon icon="mdi-shield-alert-outline" />
            <span class="ml-2">Task Configuration Fields (Conditional)</span>
        </v-card-title>

        <v-divider />
        <form>
            <v-row class="mt-4">
                <v-col cols="12" md="8">
                    <v-select label="Task Type" :items="taskTypes" v-model="selectedTaskType" variant="outlined" />
                </v-col>

                <v-col cols="12" md="4">
                    <v-text-field label="Deadline" :min="minDateTime" type="datetime-local" variant="outlined" icon-left />
                </v-col>
            </v-row>
            <v-select v-if="selectedTaskType === TaskTypes[1].value" max-width="500" label="Allowed file extensions" :items="fileExtensions" multiple />
        </form>
    </v-card>
</template>
<script setup lang="ts">
import { ref } from 'vue'
import { TaskTypes } from "../types/taskTypes"
import { FileExtensions } from "../types/fileExtensions"

const selectedTaskType = ref(null)
const taskTypes = TaskTypes
const fileExtensions = FileExtensions

const minDateTime = new Date().toISOString().slice(0, 16)
</script>