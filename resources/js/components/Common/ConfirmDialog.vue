<template>
    <v-dialog v-model="open" max-width="440">
        <v-card rounded="lg">
            <v-card-title class="pa-5">{{ title }}</v-card-title>
            <v-card-text><slot /></v-card-text>
            <v-card-actions class="pa-5 justify-end">
                <v-btn variant="text" :disabled="loading" @click="open = false"
                    >Cancel</v-btn
                >
                <v-btn
                    :color="color"
                    variant="flat"
                    :loading="loading"
                    @click="emit('confirm')"
                >
                    {{ confirmLabel }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
withDefaults(
    defineProps<{
        title: string;
        confirmLabel?: string;
        color?: string;
        loading?: boolean;
    }>(),
    {
        confirmLabel: "Confirm",
        color: "primary",
        loading: false,
    },
);

const emit = defineEmits<{ confirm: [] }>();
const open = defineModel<boolean>({ required: true });
</script>
