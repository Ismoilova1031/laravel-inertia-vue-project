<template>
    <v-app-bar>
        <template v-if="$vuetify.display.mobile" v-slot:prepend>
            <v-app-bar-nav-icon @click="$emit('toggleDrawer')"></v-app-bar-nav-icon>
        </template>

        <v-app-bar-title>Study Planner</v-app-bar-title>

        <template v-slot:append>
            <v-btn icon="mdi-magnify"></v-btn>
            <v-btn icon="mdi-exit-to-app"></v-btn>
        </template>
    </v-app-bar>
</template>
<script setup lang="ts">
defineEmits<{
    toggleDrawer: []
}>()
</script>