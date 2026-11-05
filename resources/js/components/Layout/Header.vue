<template>
    <v-app-bar>
        <template v-if="$vuetify.display.mobile" #prepend>
            <v-app-bar-nav-icon @click="emit('toggleDrawer')" />
        </template>

        <v-app-bar-title>Study Planner</v-app-bar-title>
    </v-app-bar>
</template>

<script setup lang="ts">
const emit = defineEmits<{ toggleDrawer: [] }>();
</script>
