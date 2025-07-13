<script lang="ts" setup>
import { ShiftTime } from '@/pages/shifts/types/shiftTypes';
import { format } from 'date-fns';

defineProps<{
    weeklyOverview: ShiftTime[];
}>();
</script>

<template>
    <h2 class="mb-4 text-center text-lg font-bold">Weekly Overview</h2>
    <div class="flex flex-wrap gap-2 text-center text-sm">
        <div v-for="day in weeklyOverview" :key="day.shift_date" class="flex-1 rounded bg-gray-100 p-3">
            <div class="font-semibold">{{ format(day.shift_date, 'EEEE') }}</div>
            <div class="mt-1 text-gray-700">
                {{ day.starts_at || 'Off' }}
            </div>
        </div>
    </div>
</template>

<style scoped></style>
