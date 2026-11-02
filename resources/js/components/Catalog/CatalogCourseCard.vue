<template>
    <v-card height="100%" rounded="lg" class="d-flex flex-column" elevation="2">
        <v-card-item>
            <v-card-title class="text-h6 font-weight-bold text-wrap">{{ course.title }}</v-card-title>
            <v-card-subtitle class="mt-1">{{ course.category }}</v-card-subtitle>

            <template #append>
                <v-btn
                    :icon="course.liked ? 'mdi-heart' : 'mdi-heart-outline'"
                    :color="course.liked ? 'error' : undefined"
                    :aria-label="course.liked ? 'Remove like' : 'Like'"
                    variant="text"
                    size="small"
                    @click="emit('toggle', 'like', !course.liked)"
                />
                <v-btn
                    :icon="course.planned ? 'mdi-bookmark' : 'mdi-bookmark-outline'"
                    :color="course.planned ? 'primary' : undefined"
                    :aria-label="course.planned ? 'Remove from saved' : 'Save for later'"
                    variant="text"
                    size="small"
                    @click="emit('toggle', 'planned', !course.planned)"
                />
            </template>
        </v-card-item>

        <v-card-text class="flex-grow-1">
            <p class="text-body-2 text-medium-emphasis description">{{ course.description }}</p>
        </v-card-text>

        <v-card-text class="pt-0 d-flex align-center ga-2">
            <template v-if="course.rating.count">
                <v-icon icon="mdi-star" color="warning" size="small" />
                <span class="font-weight-medium">{{ course.rating.average }}</span>
                <span class="text-body-2 text-medium-emphasis">({{ course.rating.count }})</span>
            </template>
            <span v-else class="text-body-2 text-medium-emphasis">No reviews yet</span>

            <v-spacer />
            <v-chip v-if="course.enrolled" size="small" color="success" variant="tonal">Enrolled</v-chip>
        </v-card-text>

        <v-card-actions v-if="course.enrolled" class="pa-4 pt-0">
            <v-btn color="primary" variant="flat" block @click="emit('open')">Open course</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script setup lang="ts">
import type { CatalogCourse, InterestKind } from "@/types/catalog";

defineProps<{ course: CatalogCourse }>();

const emit = defineEmits<{ toggle: [kind: InterestKind, enabled: boolean]; open: [] }>();
</script>

<style scoped>
.description {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>