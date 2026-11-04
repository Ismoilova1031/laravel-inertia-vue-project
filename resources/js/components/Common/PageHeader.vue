<template>
    <div class="d-flex align-center justify-space-between flex-wrap ga-4 mb-6">
        <div>
            <h1 class="text-h4 font-weight-bold">{{ title }}</h1>
            <p
                v-if="subtitle"
                class="text-body-1 text-medium-emphasis mt-1 mb-0"
            >
                {{ subtitle }}
            </p>
        </div>

        <div class="d-flex align-center ga-2">
            <v-btn
                v-if="backUrl"
                variant="tonal"
                color="primary"
                prepend-icon="mdi-arrow-left"
                @click="router.visit(backUrl)"
            >
                {{ backLabel }}
            </v-btn>
            <slot name="actions" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";

withDefaults(
    defineProps<{
        title: string;
        subtitle?: string;
        backUrl?: string;
        backLabel?: string;
    }>(),
    {
        backLabel: "Back",
    },
);
</script>
