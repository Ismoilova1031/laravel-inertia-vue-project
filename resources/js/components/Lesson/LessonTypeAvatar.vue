<template>
    <v-avatar :color="meta.color" variant="tonal" :size="size">
        <v-icon :icon="meta.icon" />
    </v-avatar>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { fallbackLessonMeta, lessonTypeMeta } from "../../utils/lessonType";

const props = withDefaults(defineProps<{ type: number; size?: number }>(), {
    size: 40,
});

const meta = computed(() => lessonTypeMeta[props.type] ?? fallbackLessonMeta);
</script>