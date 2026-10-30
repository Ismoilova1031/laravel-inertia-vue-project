<template>
    <div class="mb-6">
        <v-alert type="success" variant="tonal" icon="mdi-party-popper" class="mb-4">
            <div class="d-flex align-center justify-space-between ga-4 flex-wrap">
                <span>You completed this course!</span>
                <v-btn
                    v-if="course.certificate"
                    variant="flat"
                    color="success"
                    prepend-icon="mdi-certificate-outline"
                    @click="router.visit(CertificateController.show({ number: course.certificate.number }).url)"
                >
                    View certificate
                </v-btn>
                <v-btn v-else variant="flat" color="success" :loading="claiming" prepend-icon="mdi-certificate-outline" @click="claim">
                    Get certificate
                </v-btn>
            </div>
        </v-alert>

        <v-alert v-if="error" type="warning" variant="tonal" class="mb-4">{{ error }}</v-alert>

        <CourseReviewForm :student-id="studentId" :course-id="course.id" :review="course.myReview" :rating="course.rating" />
    </div>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import CourseReviewForm from "./CourseReviewForm.vue";
import CertificateController from "@/actions/App/Http/Controllers/CertificateController";
import type { LearnCourse } from "@/types/learning";

const props = defineProps<{ studentId: number; course: LearnCourse }>();

const claiming = ref(false);
const error = ref("");

function claim() {
    router.post(
        CertificateController.issue({ student: props.studentId, course: props.course.id }).url,
        {},
        {
            onStart: () => {
                claiming.value = true;
                error.value = "";
            },
            onError: (e) => (error.value = Object.values(e)[0] ?? ""),
            onFinish: () => (claiming.value = false),
        },
    );
}
</script>