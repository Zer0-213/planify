<script lang="ts" setup>
import { type WeeklyOverview } from '@/pages/dashboard/types/weeklyOverview';

defineProps<{
    weeklyOverview: WeeklyOverview;
}>();
</script>

<template>
    <h2 class="mb-4 text-center text-lg font-bold">Weekly Overview</h2>
    <div class="flex flex-wrap gap-2 text-center text-sm">
        <div v-for="day in weeklyOverview" :key="day.day" class="flex-1 rounded bg-gray-100 p-3">
            <div class="font-semibold">{{ day.day }}</div>
            <div class="mt-1 text-gray-700">
                {{ day.shift || 'Off' }}
            </div>
        </div>
    </div>
</template>

<style scoped></style>
