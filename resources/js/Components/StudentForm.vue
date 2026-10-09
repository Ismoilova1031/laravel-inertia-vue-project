
<template>
    <form @submit.prevent="submit">
        <v-card rounded="lg" border flat>
            <v-card-title class="pa-5">
                Student ma'lumotlari
            </v-card-title>

            <v-divider />

            <v-card-text class="pa-5">
                <v-row>
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.name"
                            :error-messages="form.errors.name"
                            label="Ism"
                            prepend-inner-icon="mdi-account-outline"
                            variant="outlined"
                            density="comfortable"
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.surname"
                            :error-messages="form.errors.surname"
                            label="Familiya"
                            prepend-inner-icon="mdi-account-outline"
                            variant="outlined"
                            density="comfortable"
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.email"
                            :error-messages="form.errors.email"
                            label="Email"
                            type="email"
                            prepend-inner-icon="mdi-email-outline"
                            variant="outlined"
                            density="comfortable"
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.username"
                            :error-messages="form.errors.username"
                            label="Username"
                            prepend-inner-icon="mdi-at"
                            variant="outlined"
                            density="comfortable"
                            autocomplete="username"
                        />
                    </v-col>

                    <v-col cols="12">
                        <v-divider class="mb-4" />
                        <div class="text-subtitle-1 font-weight-medium mb-4">
                            Xavfsizlik
                        </div>
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.password"
                            :error-messages="form.errors.password"
                            label="Password"
                            type="password"
                            prepend-inner-icon="mdi-lock-outline"
                            variant="outlined"
                            density="comfortable"
                            autocomplete="new-password"
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="form.password_confirmation"
                            :error-messages="form.errors.password_confirmation"
                            label="Password Confirm"
                            type="password"
                            prepend-inner-icon="mdi-lock-check-outline"
                            variant="outlined"
                            density="comfortable"
                            autocomplete="new-password"
                        />
                    </v-col>
                </v-row>
            </v-card-text>

            <v-divider />

            <v-card-actions class="pa-5 justify-end">
                <v-btn
                    type="button"
                    variant="text"
                    @click="emit('cancel')"
                >
                    Bekor qilish
                </v-btn>

                <v-btn
                    type="submit"
                    color="primary"
                    variant="flat"
                    prepend-icon="mdi-account-plus-outline"
                >
                    {{ submitLabel }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </form>
</template>

<script setup lang="ts">
import type { StudentFormInstance } from '../forms/studentForm';

defineProps<{
    form: StudentFormInstance;
    submitLabel: string;
    submit: () => void;
}>();

const emit = defineEmits<{
    cancel: [];
}>();
</script>