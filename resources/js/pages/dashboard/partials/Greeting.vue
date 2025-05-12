<script lang="ts" setup>
import { defineProps } from 'vue';
import type { User } from '@/types/user';

const getGreeting = () => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Morning';
    if (hour < 17) return 'Afternoon';
    return 'Evening';
};

const greeting = getGreeting();

defineProps<{
    user: User;
}>();
</script>

<template>
    <div class="text-center text-2xl font-bold">Good {{ greeting }} {{ user?.name }}!</div>
</template>
