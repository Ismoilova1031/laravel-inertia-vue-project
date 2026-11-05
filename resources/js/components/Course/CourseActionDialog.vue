<template>
    <ConfirmDialog
        v-if="course && meta"
        v-model="open"
        :title="meta.title"
        :confirm-label="meta.confirm"
        :color="meta.color"
        :loading="loading"
        @confirm="emit('confirm')"
    >
        <span class="font-weight-bold">"{{ course.title }}"</span>
        <br />
        {{ meta.message }}
    </ConfirmDialog>
</template>

<script setup lang="ts">
import { computed } from "vue";
import ConfirmDialog from "@/components/Common/ConfirmDialog.vue";
import type { Course } from "@/types/course";
import { courseActionMeta } from "@/utils/courseStatus";

const props = defineProps<{ course: Course | null; loading: boolean }>();
const emit = defineEmits<{ confirm: [] }>();

const open = defineModel<boolean>({ required: true });
const meta = computed(() =>
    props.course ? courseActionMeta[props.course.status.value] : null,
);
</script>
