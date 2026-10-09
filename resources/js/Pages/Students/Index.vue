<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import StudentList from '../../components/StudentList.vue'
import type { StudentRow } from '../../types/student'
import { create } from '../../actions/App/Http/Controllers/StudentController.js'

defineProps<{ students: StudentRow[] }>()
</script>

<template>
  <v-container>

    <Head title="Studentlar" />

    <v-card variant="text" class="mb-4 pa-2 d-flex flex-row gap-2 align-center justify-space-between">
      <div>
        <v-card-title>Studentlar ro'yxati</v-card-title>
        <v-card-subtitle>Ushbu sahifada barcha studentlar ro'yxatini ko'rishingiz mumkin.</v-card-subtitle>
      </div>
      <v-btn variant="tonal" color="primary" class="mb-4" :href="create().url">
        <v-icon start>mdi-plus</v-icon>
        Yangi student qo'shish
      </v-btn>
    </v-card>
    <StudentList :students="students" />
  </v-container>
</template>