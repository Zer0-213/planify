<script lang="ts" setup>
import { ShiftTime } from '@/pages/shifts/types/shiftTypes';
import { format } from 'date-fns';

defineProps<{
    upcomingShifts: ShiftTime[];
}>();
</script>

<template>
    <div class="w-3/4 rounded-xl bg-white p-5 shadow-lg">
        <h2 class="mb-4 text-center text-lg font-bold">Upcoming Shifts</h2>
        <ul class="space-y-3">
            <li v-for="shift in upcomingShifts" :key="shift.shift_date" class="flex justify-between">
                <!--                <span>{{ shift.role || 'Staff Member' }}</span>-->
                <span class="text-sm text-gray-500">{{ format(shift.shift_date, 'EEEEEE') }} - {{ shift.starts_at }} to {{ shift.ends_at }}</span>
            </li>
        </ul>
        <div v-if="!upcomingShifts?.length" class="text-gray-500">No upcoming shifts.</div>
    </div>
</template>

<style scoped></style>
