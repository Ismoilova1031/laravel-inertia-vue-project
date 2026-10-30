<template>
    <v-container class="login-page d-flex align-center justify-center">
        <v-card rounded="lg" class="border pa-8 w-100" max-width="420" elevation="0">
            <div class="text-center mb-6">
                <v-icon icon="mdi-book-open-page-variant" size="48" color="primary" class="mb-2" />
                <h1 class="text-h5 font-weight-bold">Study Planner</h1>
                <p class="text-body-2 text-medium-emphasis mt-1">Sign in to continue</p>
            </div>

            <form @submit.prevent="submit">
                <v-text-field
                    v-model="form.username"
                    label="Username"
                    variant="outlined"
                    autocomplete="username"
                    prepend-inner-icon="mdi-account-outline"
                    :error-messages="form.errors.username"
                    autofocus
                />
                <v-text-field
                    v-model="form.password"
                    label="Password"
                    type="password"
                    variant="outlined"
                    autocomplete="current-password"
                    prepend-inner-icon="mdi-lock-outline"
                    :error-messages="form.errors.password"
                    class="mt-2"
                />
                <v-btn type="submit" color="primary" block size="large" class="mt-4" :loading="form.processing">
                    Sign in
                </v-btn>
            </form>
        </v-card>
    </v-container>
</template>

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import AuthController from "@/actions/App/Http/Controllers/AuthController";
import BlankLayout from "@/layouts/BlankLayout.vue";

defineOptions({ layout: BlankLayout });

const form = useForm({ username: "", password: "" });

function submit() {
    form.post(AuthController.login().url, { onFinish: () => form.reset("password") });
}
</script>

<style scoped>
.login-page {
    min-height: 100vh;
}
</style>