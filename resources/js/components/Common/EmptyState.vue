<template>
    <v-card class="pa-8 text-center" variant="outlined" rounded="lg">
        <v-icon :icon="icon" size="64" class="mb-4" />
        <h2 class="text-h5 mb-2">{{ title }}</h2>
        <p v-if="text" class="text-body-1 text-medium-emphasis mb-0">
            {{ text }}
        </p>
        <div v-if="$slots.default" class="mt-6"><slot /></div>
    </v-card>
</template>

<script setup lang="ts">
withDefaults(defineProps<{ title: string; text?: string; icon?: string }>(), {
    icon: "mdi-book-open-outline",
});
</script>
