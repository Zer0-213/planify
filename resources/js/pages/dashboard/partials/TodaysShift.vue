<script lang="ts" setup>
import { ShiftTime } from '@/pages/shifts/types/shiftTypes';
import { formatShiftTime } from '@/pages/shifts/utils/shiftTimeUtils';
import { computed, defineProps } from 'vue';

const props = defineProps<{
    shift: ShiftTime;
}>();

const formattedStart = computed(() => (props.shift.starts_at ? formatShiftTime(props.shift.starts_at, 'HH:mm') : 'NA'));
const formattedEnd = computed(() => (props.shift.ends_at ? formatShiftTime(props.shift.ends_at, 'HH:mm') : 'NA'));
</script>

<template>
    <div class="w-1/2 rounded-xl bg-white p-5 text-center shadow-lg">
        <div class="mb-1 text-lg font-semibold">Your Shift Today</div>
        <div class="text-gray-700">
            <span v-if="shift"> {{ formattedStart }} - {{ formattedEnd }} </span>
            <span v-else> No shift today 🎉 </span>
        </div>
    </div>
</template>
