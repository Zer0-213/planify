<script lang="ts" setup>
import { defineProps } from 'vue';

defineProps<{
    shift: {
        start_time: string;
        end_time: string;
        location: string;
    } | null;
}>();
</script>

<template>
    <div class="w-1/2 rounded-xl bg-white p-5 text-center shadow-lg">
        <div class="mb-1 text-lg font-semibold">Your Shift Today</div>
        <div class="text-gray-700">
            <span v-if="shift"> {{ shift.start_time }} - {{ shift.end_time }} at {{ shift.end_time }} </span>
            <span v-else> No shift today 🎉 </span>
        </div>
    </div>
</template>
