<template>
    <div>
        <v-label class="mb-3"> Options Configuration </v-label>

        <div v-if="errors?.options" class="text-error text-caption mb-2">
            {{ errors.options }}
        </div>
        <v-radio-group v-if="type === 1" v-model="correctOptionId" hide-details>
            <div v-for="(option, index) in options" :key="option.id" class="d-flex align-center ga-2 mb-3">
                <v-radio :value="option.id" class="flex-grow-0"/>

                <v-text-field v-model="option.text" label="Option" placeholder="Enter option..." variant="outlined"
                    hide-details="auto" :error-messages="errors?.[`${index}.text`]" />

                <v-btn icon="mdi-delete" variant="text" color="error" size="small" @click="removeOption(option.id)" />
            </div>
        </v-radio-group>


        <div v-else-if="type === 2">
            <div v-for="(option, index) in options" :key="option.id" class="d-flex align-center ga-2 mb-3">
                <v-checkbox v-model="option.is_correct" hide-details />

                <v-text-field v-model="option.text" label="Option" placeholder="Enter option..." variant="outlined" hide-details="auto" :error-messages="errors?.[`${index}.text`]" />

                <v-btn icon="mdi-delete" variant="text" color="error" size="small" @click="removeOption(option.id)" />

            </div>
        </div>

        <v-btn variant="text" prepend-icon="mdi-plus" @click="addOption">
            Add Option
        </v-btn>
    </div>
</template>
<script setup lang="ts">
import { computed } from "vue";
import type { OptionFormData } from "../forms/optionForm";

defineProps<{
    type: 1 | 2;
    errors?: Record<string, string>;
}>();

const options = defineModel<OptionFormData[]>({
    required: true,
});

const correctOptionId = computed({
    get() {
        return options.value.find(option => option.is_correct)?.id ?? null;
    },

    set(id: number | string | null) {
        options.value.forEach(option => {
            option.is_correct = option.id === id;
        });
    },
});

function addOption() {
    options.value = [
        ...options.value,
        {
            id: Date.now(),
            text: "",
            is_correct: false,
        },
    ];
}

function removeOption(id: number | string) {
    options.value = options.value.filter(
        option => option.id !== id
    );
}
</script>