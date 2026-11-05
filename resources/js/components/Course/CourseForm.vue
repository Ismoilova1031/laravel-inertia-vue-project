<template>
    <form @submit.prevent="emit('submit')">
        <v-text-field
            v-model="form.title"
            variant="outlined"
            label="Course Title"
            :error-messages="form.errors.title"
        />
        <v-textarea
            v-model="form.description"
            variant="outlined"
            label="Course Description"
            rows="4"
            class="mt-4"
            :error-messages="form.errors.description"
        />

        <v-row class="mt-1">
            <v-col cols="12" :md="showStatus ? 6 : 12">
                <v-select
                    v-model="form.category"
                    :items="categories"
                    item-title="label"
                    item-value="value"
                    variant="outlined"
                    label="Course Category"
                    :error-messages="form.errors.category"
                />
            </v-col>
            <v-col v-if="showStatus" cols="12" md="6">
                <v-select
                    v-model="form.status"
                    :items="statuses"
                    item-title="label"
                    item-value="value"
                    variant="outlined"
                    label="Course Status"
                    :error-messages="form.errors.status"
                />
            </v-col>
        </v-row>

        <div class="d-flex justify-end mt-4">
            <v-btn
                type="submit"
                color="primary"
                variant="flat"
                :loading="form.processing"
                >{{ submitLabel }}</v-btn
            >
        </div>
    </form>
</template>

<script setup lang="ts">
import type { CourseFormInstance } from "@/composables/useCourseForm";
import type { SelectOption } from "@/types/common";

defineProps<{
    form: CourseFormInstance;
    categories: SelectOption[];
    statuses: SelectOption[];
    submitLabel: string;
    showStatus?: boolean;
}>();

const emit = defineEmits<{ submit: [] }>();
</script>
