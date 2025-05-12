<script lang="ts" setup>
import { type UpcomingShifts } from '@/pages/dashboard/types/upcomingShifts';

defineProps<{
    upcomingShifts: UpcomingShifts;
}>();
</script>

<template>
    <div class="w-3/4 rounded-xl bg-white p-5 shadow-lg">
        <h2 class="mb-4 text-center text-lg font-bold">Upcoming Shifts</h2>
        <ul class="space-y-3">
            <li v-for="shift in upcomingShifts" :key="shift.id" class="flex justify-between">
                <span>{{ shift.role || 'Staff Member' }}</span>
                <span class="text-sm text-gray-500">{{ shift.date }} - {{ shift.start_time }} to {{ shift.end_time }}</span>
            </li>
        </ul>
        <div v-if="!upcomingShifts.length" class="text-gray-500">No upcoming shifts.</div>
    </div>
</template>

<style scoped></style>
