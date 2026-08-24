<template>
    <v-app>

        <Header @toggle-drawer="drawer = !drawer"/>

        <Sidebar v-model="drawer" />

        <v-main>
            <slot />
        </v-main>
    </v-app>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Header from '../components/Header.vue'
import Sidebar from '../components/Sidebar.vue'

const drawer = ref(true)
</script>