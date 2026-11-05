<template>
    <v-hover v-slot="{ isHovering, props: hover }">
        <v-card
            v-bind="hover"
            height="100%"
            rounded="lg"
            class="d-flex flex-column"
            :elevation="isHovering ? 6 : 2"
            :class="{
                archived: course.status.value === COURSE_STATUS.ARCHIVED,
            }"
        >
            <v-card-item>
                <v-card-title class="text-h6 font-weight-bold text-wrap">{{
                    course.title
                }}</v-card-title>
                <v-card-subtitle class="mt-1"
                    >Course #{{ course.id }}</v-card-subtitle
                >
            </v-card-item>

            <v-card-text class="flex-grow-1">
                <div class="d-flex align-center flex-wrap ga-2 mb-4">
                    <v-chip color="primary" size="small" variant="tonal">{{
                        course.category
                    }}</v-chip>
                    <v-chip
                        :color="courseStatusColor[course.status.value]"
                        size="small"
                        variant="tonal"
                    >
                        {{ course.status.label }}
                    </v-chip>
                    <v-spacer />
                    <v-btn
                        :icon="action.icon"
                        :color="action.color"
                        :aria-label="action.title"
                        variant="text"
                        size="small"
                        @click="emit('action')"
                    />
                </div>
                <p class="text-body-2 text-medium-emphasis description">
                    {{ course.description }}
                </p>
            </v-card-text>

            <v-card-actions class="pa-4">
                <v-btn
                    color="primary"
                    variant="outlined"
                    block
                    @click="emit('open')"
                    >View Course</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-hover>
</template>

<script setup lang="ts">
import { computed } from "vue";
import type { Course } from "@/types/course";
import {
    COURSE_STATUS,
    courseActionMeta,
    courseStatusColor,
} from "@/utils/courseStatus";

const props = defineProps<{ course: Course }>();
const emit = defineEmits<{ open: []; action: [] }>();

const action = computed(() => courseActionMeta[props.course.status.value]);
</script>

<style scoped>
.archived {
    opacity: 0.7;
}

.description {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
