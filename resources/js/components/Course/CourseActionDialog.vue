<template>
    <v-dialog v-model="open" max-width="500">
        <v-card v-if="course && meta" rounded="lg">
            <v-card-title class="text-h6">{{ meta.title }}</v-card-title>

            <v-card-text>
                <span class="font-weight-bold">"{{ course.title }}"</span>
                <br />
                {{ meta.message }}
            </v-card-text>

            <v-card-actions class="px-6 py-4">
                <v-spacer />
                <v-btn variant="text" :disabled="loading" @click="open = false">Cancel</v-btn>
                <v-btn :color="meta.color" variant="flat" :loading="loading" @click="emit('confirm')">
                    {{ meta.confirm }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { computed } from "vue";
import type { Course } from "@/types/course";
import { courseActionMeta } from "@/utils/courseStatus";

const props = defineProps<{ course: Course | null; loading: boolean }>();
const emit = defineEmits<{ confirm: [] }>();

const open = defineModel<boolean>({ required: true });
const meta = computed(() => (props.course ? courseActionMeta[props.course.status.value] : null));
</script>