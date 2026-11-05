<template>
    <div class="certificate-page">
        <Head :title="`Certificate ${certificate.number}`" />

        <v-container class="py-6 wrap">
            <div class="d-flex align-center flex-wrap ga-3 mb-5">
                <v-btn
                    v-if="auth"
                    variant="text"
                    prepend-icon="mdi-arrow-left"
                    class="px-0"
                    @click="goBack"
                    >Back</v-btn
                >

                <div class="flex-grow-1">
                    <h1 class="text-h5 font-weight-bold">
                        Certificate of completion
                    </h1>
                    <v-chip
                        size="small"
                        color="success"
                        variant="tonal"
                        prepend-icon="mdi-check-decagram"
                        class="mt-1"
                    >
                        Verified · {{ certificate.number }}
                    </v-chip>
                </div>

                <v-btn
                    color="primary"
                    prepend-icon="mdi-file-pdf-box"
                    :loading="busy === 'pdf'"
                    :disabled="busy !== null"
                    @click="downloadPdf"
                >
                    Download PDF
                </v-btn>
                <v-btn
                    variant="outlined"
                    prepend-icon="mdi-image-outline"
                    :loading="busy === 'png'"
                    :disabled="busy !== null"
                    @click="downloadPng"
                >
                    Download PNG
                </v-btn>
            </div>

            <v-alert
                v-if="error"
                type="error"
                variant="tonal"
                class="mb-4"
                closable
                >{{ error }}</v-alert
            >

            <CertificateCard
                ref="card"
                :certificate="certificate"
                :verify-url="verifyUrl"
            />
        </v-container>
    </div>
</template>

<script setup lang="ts">
import { Head, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import CertificateController from "@/actions/App/Http/Controllers/CertificateController";
import CertificateCard from "@/components/Certificate/CertificateCard.vue";
import { useCertificateExport } from "@/composables/useCertificateExport";
import type { AuthUser } from "@/types/auth";
import type { CertificateData } from "@/types/certificate";

const props = defineProps<{ certificate: CertificateData }>();

const card = ref<InstanceType<typeof CertificateCard> | null>(null);

const verifyUrl = computed(
    () =>
        `${window.location.origin}${CertificateController.show({ number: props.certificate.number }).url}`,
);

const { busy, error, downloadPdf, downloadPng } = useCertificateExport(
    () => card.value?.getSheet(),
    () => `certificate-${props.certificate.number}`,
);

const auth = computed(() => usePage().props.auth as AuthUser | null);
const goBack = () => window.history.back();
</script>

<style scoped>
.certificate-page {
    min-height: 100vh;
    background: #eef1f5;
}

.wrap {
    max-width: 1200px;
}
</style>
