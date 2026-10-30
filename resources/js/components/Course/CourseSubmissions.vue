<template>
    <v-card rounded="lg" class="border" elevation="0">
        <v-list class="py-0" lines="two">
            <template v-for="(row, i) in submissions" :key="row.id">
                <v-divider v-if="i > 0" />
                <v-list-item class="px-5 py-3" @click="selectedId = row.id">
                    <v-list-item-title class="font-weight-medium">
                        {{ row.student.fullName }}
                    </v-list-item-title>
                    <v-list-item-subtitle class="mt-1">
                        {{ row.lesson.title }} · {{ row.taskType.label }} · attempt {{ row.attempt }} · {{ row.submittedAt }}
                    </v-list-item-subtitle>
                    <template #append>
                        <span v-if="row.score !== null" class="text-body-2 mr-3">{{ row.score }} / {{ row.maxScore }}</span>
                        <v-chip size="small" variant="tonal" :color="statusColor(row.status.value)">
                            {{ row.status.label }}
                        </v-chip>
                    </template>
                </v-list-item>
            </template>

            <v-list-item v-if="submissions.length === 0" class="text-center py-10">
                <v-list-item-title>No submissions yet.</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-card>

    <SubmissionReviewDialog :submission="selected" @close="selectedId = null" />
</template>

<script setup lang="ts">
import { computed, ref } from "vue";
import SubmissionReviewDialog from "./SubmissionReviewDialog.vue";
import type { SubmissionRow } from "@/types/submission";

const props = defineProps<{ submissions: SubmissionRow[] }>();

const selectedId = ref<number | null>(null);
const selected = computed(() => props.submissions.find((s) => s.id === selectedId.value) ?? null);

const statusColor = (status: number) => (status === 1 ? "success" : status === 2 ? "error" : "warning");
</script>