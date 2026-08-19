<template>
    <div>
        <!-- Hidden file input -->
        <input
            ref="fileInput"
            type="file"
            accept="video/*"
            class="d-none"
            @change="handleFileChange"
        />

        <!-- Upload -->
        <v-file-upload
            v-if="!videoUrl"
            v-model="video"
            label="Upload Video"
            accept="video/*"
            show-size
            :error-messages="errorMessages"
            @update:model-value="handleVideo"
        />

        <!-- Preview -->
        <v-card
            v-else
            variant="outlined"
            rounded="lg"
        >
            <video
                :src="videoUrl"
                controls
                class="video-preview"
            />

            <v-card-actions class="px-4 py-3">
                <div class="text-body-2 text-truncate">
                    {{ video?.name }}
                </div>

                <v-spacer />

                <!-- Replace -->
                <v-btn
                    variant="text"
                    prepend-icon="mdi-swap-horizontal"
                    @click="openFilePicker"
                >
                    Replace
                </v-btn>

                <!-- Delete -->
                <v-btn
                    variant="text"
                    color="error"
                    prepend-icon="mdi-delete-outline"
                    @click="deleteVideo"
                >
                    Delete
                </v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, ref } from 'vue';

const props = defineProps<{
    modelValue: File | null;
    errorMessages?: string | string[];
}>();

const emit = defineEmits<{
    'update:modelValue': [file: File | null];
}>();

const video = ref<File | null>(props.modelValue);

const fileInput = ref<HTMLInputElement | null>(null);

const videoUrl = computed(() => {
    if (!video.value) {
        return null;
    }

    return URL.createObjectURL(video.value);
});

function handleVideo(file: File | File[] | null) {
    if (Array.isArray(file)) {
        video.value = file[0] ?? null;
    } else {
        video.value = file;
    }

    emit('update:modelValue', video.value);
}

function openFilePicker() {
    fileInput.value?.click();
}

function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement;

    const file = target.files?.[0] ?? null;

    if (!file) {
        return;
    }

    video.value = file;

    emit('update:modelValue', file);

    // Keyin aynan shu faylni yana tanlashga ham imkon beradi
    target.value = '';
}

function deleteVideo() {
    video.value = null;

    emit('update:modelValue', null);
}

onBeforeUnmount(() => {
    if (videoUrl.value) {
        URL.revokeObjectURL(videoUrl.value);
    }
});
</script>

<style scoped>
.video-preview {
    display: block;
    width: 100%;
    max-height: 450px;
    object-fit: contain;
    background: #000;
}
</style>