<template>
    <div>
        <div class="d-flex align-center justify-space-between mb-3">
            <v-chip size="small" variant="tonal" prepend-icon="mdi-paperclip">
                {{ extension?.toUpperCase() }}
            </v-chip>
            <v-btn
                size="small"
                variant="text"
                prepend-icon="mdi-download"
                :href="SubmissionController.file({ submission: submissionId }).url"
            >
                Download
            </v-btn>
        </div>

        <iframe
            v-if="kind === 'frame'"
            :src="previewUrl"
            class="preview-frame border rounded-lg"
            title="Submitted file"
        />

        <div v-else-if="kind === 'image'" class="text-center">
            <img :src="previewUrl" alt="Submitted file" class="preview-image rounded-lg border" />
        </div>

        <v-alert v-else type="info" variant="tonal" icon="mdi-file-eye-outline">
            Bu faylni brauzerda ko'rib bo'lmaydi. Iltimos, faylni yuklab oling va mos dastur yordamida oching.
        </v-alert>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import SubmissionController from "../actions/App/Http/Controllers/SubmissionController";

const props = defineProps<{ submissionId: number; extension: string | null }>();

const kind = computed<"frame" | "image" | "none">(() => {
    if (props.extension === "pdf" || props.extension === "txt") return "frame";
    if (["jpg", "jpeg", "png", "gif"].includes(props.extension ?? "")) return "image";
    return "none";
});

const previewUrl = computed(() => SubmissionController.preview({ submission: props.submissionId }).url);
</script>

<style scoped>
.preview-frame {
    width: 100%;
    height: 60vh;
}

.preview-image {
    max-width: 100%;
    max-height: 60vh;
}
</style>