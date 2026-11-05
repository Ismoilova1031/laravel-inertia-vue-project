<template>
    <v-card
        link
        rounded="lg"
        class="border border-dashed bg-transparent"
        elevation="0"
    >
        <div class="d-flex align-center justify-center ga-3 px-5 py-4">
            <v-icon :icon="icon" />
            <span class="font-weight-medium">{{ label }}</span>
        </div>
    </v-card>
</template>

<script setup lang="ts">
withDefaults(defineProps<{ label: string; icon?: string }>(), {
    icon: "mdi-plus",
});
</script>
