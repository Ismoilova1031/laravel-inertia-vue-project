<template>
    <form @submit.prevent="emit('submit', { text })">
        <v-textarea v-model="text" label="Your contribution" variant="outlined" rows="6" auto-grow />
        <div class="d-flex justify-end">
            <v-btn
                type="submit"
                color="primary"
                :disabled="text.trim() === ''"
                :loading="loading"
                prepend-icon="mdi-send"
            >
                Post
            </v-btn>
        </div>
    </form>
</template>

<script setup lang="ts">
import { ref } from "vue";

defineProps<{ loading: boolean }>();
const emit = defineEmits<{ submit: [payload: { text: string }] }>();

const text = ref("");
</script>