<template>
    <div>
        <input
            ref="picker"
            type="file"
            accept="video/*"
            class="d-none"
            @change="onPick"
        />

        <v-file-upload
            v-if="!previewUrl"
            :model-value="file"
            label="Upload Video"
            accept="video/*"
            show-size
            :error-messages="errorMessages"
            @update:model-value="setFile"
        />

        <v-card v-else variant="outlined" rounded="lg">
            <video :src="previewUrl" controls class="preview" />

            <v-card-actions class="px-4 py-3">
                <div class="text-body-2 text-truncate">{{ file?.name }}</div>
                <v-spacer />
                <v-btn
                    variant="text"
                    prepend-icon="mdi-swap-horizontal"
                    @click="picker?.click()"
                    >Replace</v-btn
                >
                <v-btn
                    variant="text"
                    color="error"
                    prepend-icon="mdi-delete-outline"
                    @click="clear"
                    >Delete</v-btn
                >
            </v-card-actions>
        </v-card>
    </div>
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, ref, watch } from "vue";

defineProps<{ errorMessages?: string | string[] }>();

const file = defineModel<File | undefined>();
const existingUrl = defineModel<string | null>("existingUrl");

const picker = ref<HTMLInputElement | null>(null);
const objectUrl = ref<string | null>(null);

watch(
    file,
    (value) => {
        if (objectUrl.value) URL.revokeObjectURL(objectUrl.value);
        objectUrl.value = value ? URL.createObjectURL(value) : null;
    },
    { immediate: true },
);

onBeforeUnmount(() => objectUrl.value && URL.revokeObjectURL(objectUrl.value));

const previewUrl = computed(() => objectUrl.value ?? existingUrl.value ?? null);

function setFile(value: File | File[] | null | undefined) {
    const next = Array.isArray(value) ? value[0] : (value ?? undefined);

    file.value = next;
    if (next) existingUrl.value = null;
}

function onPick(event: Event) {
    const input = event.target as HTMLInputElement;

    setFile(input.files?.[0]);
    input.value = "";
}

function clear() {
    file.value = undefined;
    existingUrl.value = null;
}
</script>

<style scoped>
.preview {
    display: block;
    width: 100%;
    max-height: 450px;
    object-fit: contain;
    background: #000;
}
</style>
