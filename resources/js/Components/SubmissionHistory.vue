<template>
    <div>
        <h3 class="text-subtitle-1 font-weight-bold mb-2">Your attempts</h3>
        <v-list class="border rounded-lg py-0" lines="two">
            <template v-for="(s, i) in submissions" :key="s.attempt">
                <v-divider v-if="i > 0" />
                <v-list-item>
                    <v-list-item-title class="font-weight-medium">
                        Attempt {{ s.attempt }}
                        <span v-if="s.score !== null && maxScore > 0" class="text-medium-emphasis">
                            · {{ s.score }} / {{ maxScore }}
                        </span>
                    </v-list-item-title>
                    <v-list-item-subtitle class="mt-1">
                        {{ s.submittedAt }}
                        <template v-if="s.hasFile"> · file uploaded</template>
                        <template v-if="s.teacherComment"> · “{{ s.teacherComment }}”</template>
                    </v-list-item-subtitle>
                    <template #append>
                        <v-chip size="small" variant="tonal" :color="statusColor(s.status.value)">
                            {{ s.status.label }}
                        </v-chip>
                    </template>
                </v-list-item>
            </template>
        </v-list>
    </div>
</template>

<script setup lang="ts">
import type { LearnSubmission } from "../types/learning";

defineProps<{ submissions: LearnSubmission[]; maxScore: number }>();

const statusColor = (status: number) => (status === 1 ? "success" : status === 2 ? "error" : "warning");
</script>