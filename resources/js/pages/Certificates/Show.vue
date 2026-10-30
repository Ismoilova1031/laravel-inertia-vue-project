<template>
    <v-container class="py-8 certificate-page">
        <v-card rounded="lg" class="border certificate pa-10 text-center" elevation="0">
            <v-icon icon="mdi-certificate-outline" size="64" color="primary" class="mb-4" />
            <p class="text-overline text-medium-emphasis">Certificate of completion</p>
            <p class="text-body-1 mt-6">This certifies that</p>
            <h1 class="text-h3 font-weight-bold my-3">{{ certificate.studentName }}</h1>
            <p class="text-body-1">has successfully completed the course</p>
            <h2 class="text-h5 font-weight-bold my-3">{{ certificate.courseTitle }}</h2>

            <div v-if="certificate.grade" class="grade my-6">
                <div class="text-overline text-medium-emphasis">Grade</div>
                <div class="text-h2 font-weight-bold text-primary">{{ certificate.grade }}</div>
                <div class="text-body-2 text-medium-emphasis">{{ certificate.percent }}%</div>
            </div>

            <v-divider class="my-6" />

            <div class="d-flex justify-space-between text-body-2 text-medium-emphasis">
                <span>Issued: {{ certificate.issuedAt }}</span>
                <span>No. {{ certificate.number }}</span>
            </div>
        </v-card>

        <div class="d-flex justify-center mt-6 no-print">
            <v-btn color="primary" prepend-icon="mdi-printer" @click="print">Print / Save as PDF</v-btn>
        </div>
    </v-container>
</template>

<script setup lang="ts">
import BlankLayout from "@/layouts/BlankLayout.vue";

defineOptions({ layout: BlankLayout });

defineProps<{
    certificate: {
        number: string;
        grade: string | null;
        percent: number | null;
        issuedAt: string;
        studentName: string;
        courseTitle: string;
    };
}>();

const print = () => window.print();
</script>

<style scoped>
.certificate-page {
    max-width: 820px;
}

.certificate {
    border-width: 3px !important;
}

@media print {
    .no-print {
        display: none;
    }
}
</style>