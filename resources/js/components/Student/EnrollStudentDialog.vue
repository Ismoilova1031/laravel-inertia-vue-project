<template>
    <v-dialog v-model="open" max-width="480">
        <v-card rounded="lg">
            <v-card-title class="pa-5">Enroll a student</v-card-title>
            <v-card-text>
                <v-autocomplete
                    v-model="selectedId"
                    :items="students"
                    item-value="id"
                    :item-title="label"
                    label="Student"
                    variant="outlined"
                    no-data-text="No students available"
                />
            </v-card-text>
            <v-card-actions class="pa-5 justify-end">
                <v-btn variant="text" :disabled="loading" @click="open = false"
                    >Cancel</v-btn
                >
                <v-btn
                    color="primary"
                    variant="flat"
                    :loading="loading"
                    :disabled="selectedId === null"
                    @click="selectedId !== null && emit('enroll', selectedId)"
                >
                    Enroll
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import type { Student } from "@/types/student";

defineProps<{ students: Student[]; loading: boolean }>();
const emit = defineEmits<{ enroll: [studentId: number] }>();

const open = defineModel<boolean>({ required: true });
const selectedId = ref<number | null>(null);

watch(open, (value) => {
    if (!value) selectedId.value = null;
});

const label = (s: Student) => `${s.name} ${s.surname} (@${s.username})`;
</script>
