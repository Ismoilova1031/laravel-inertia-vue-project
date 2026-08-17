<template>
    <v-container>
        <v-card>
            <v-card-title>Edit Course</v-card-title>
            <v-card-text>
                <form @submit.prevent="submit">
                    <v-text-field name="title" placeholder="Course Title" v-model="form.title"
                        :error-messages="form.errors.title" />
                    <v-textarea name="description" placeholder="Course Description" v-model="form.description"
                        :error-messages="form.errors.description" />
                    <v-select v-model="form.category" :items="categories" item-title="title" item-value="value"
                        label="Course Category" :error-messages="form.errors.category" />
                    <v-select v-model="form.status" :items="statuses" item-title="title" item-value="value"
                        label="Course Status" :error-messages="form.errors.status" />
                    <v-btn type="submit">Update Course</v-btn>
                </form>
            </v-card-text>
        </v-card>
    </v-container>
</template>
<script setup lang="ts">
import { useCourseForm } from "../../forms/courseForm";

const props = defineProps<{
    course: {
        id: number;
        title: string;
        description: string;
        category: number;
        status: number;
    };
    categories: {
        title: string;
        value: number;
    }[];
    statuses: {
        title: string;
        value: number;
    }[];
}>();
const { form, submit } = useCourseForm({
        'title': props.course.title,
        'description': props.course.description,
        'category': props.course.category.value,
        'status': props.course.status.value,
    },
    props.course.id
);
</script>
