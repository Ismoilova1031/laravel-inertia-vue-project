<template>
    <v-card rounded="lg" class="border pa-5" elevation="0">
        <h3 class="text-subtitle-1 font-weight-bold mb-1">Rate this course</h3>
        <p v-if="rating.count" class="text-body-2 text-medium-emphasis mb-3">
            Average {{ rating.average }} / 5 · {{ rating.count }} review(s)
        </p>

        <v-alert v-if="error" type="error" variant="tonal" class="mb-3">{{ error }}</v-alert>

        <v-rating v-model="form.rating" color="warning" hover :half-increments="false" class="mb-2" />
        <v-textarea v-model="form.comment" label="Comment (optional)" variant="outlined" rows="3" auto-grow hide-details />

        <div class="d-flex justify-end mt-3">
            <v-btn color="primary" :disabled="form.rating === 0" :loading="form.processing" @click="save">
                {{ saved ? "Update review" : "Submit review" }}
            </v-btn>
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import CourseReviewController from "../../actions/App/Http/Controllers/CourseReviewController";
import type { LearnCourse } from "../../types/learning";

const props = defineProps<{ studentId: number; courseId: number; review: LearnCourse["myReview"]; rating: LearnCourse["rating"] }>();

const form = useForm({ rating: props.review?.rating ?? 0, comment: props.review?.comment ?? "" });
const saved = computed(() => props.review !== null);
const error = ref("");

function save() {
    error.value = "";
    form.post(CourseReviewController.store({ student: props.studentId, course: props.courseId }).url, {
        preserveScroll: true,
        onError: (e) => (error.value = Object.values(e)[0] ?? ""),
    });
}
</script>