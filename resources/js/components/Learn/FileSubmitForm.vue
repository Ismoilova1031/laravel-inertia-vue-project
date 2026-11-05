<template>
    <form @submit.prevent="emit('submit', { file })">
        <v-file-input
            v-model="file"
            label="Choose a file"
            variant="outlined"
            prepend-icon=""
            prepend-inner-icon="mdi-paperclip"
            :accept="accept"
            :hint="`Allowed: ${extensions.join(', ')} (max 10 MB)`"
            persistent-hint
            :error-messages="tooBig ? 'File is larger than 10 MB' : undefined"
        />
        <div class="d-flex justify-end mt-4">
            <v-btn
                type="submit"
                color="primary"
                :disabled="!file || tooBig"
                :loading="loading"
                prepend-icon="mdi-upload"
            >
                Upload
            </v-btn>
        </div>
    </form>
</template>

<script setup lang="ts">
import { computed, ref } from "vue";

const props = defineProps<{ extensions: string[]; loading: boolean }>();
const emit = defineEmits<{ submit: [payload: { file: File | null }] }>();

const tooBig = computed(() => (file.value?.size ?? 0) > 10 * 1024 * 1024);
const file = ref<File | null>(null);
const accept = computed(() => props.extensions.map((e) => `.${e}`).join(","));
</script>
