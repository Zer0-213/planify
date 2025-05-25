<!-- resources/js/pages/shifts/table/ShiftCell.vue -->
<script lang="ts" setup>
import { Input } from '@/components/ui/input';
import { useShiftTime } from '@/pages/shifts/composables/useShiftTime';
import { ShiftTime } from '@/pages/shifts/types/shiftTypes';
import { computed } from 'vue';

type Props = {
    startTime: string;
    endTime: string;
    date: string;
    disabled?: boolean;
};

const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'updateTime', payload: ShiftTime): void;
}>();

const { localStart, localEnd } = useShiftTime({
    startTime: props.startTime,
    endTime: props.endTime,
    date: props.date,
    emit: (payload) => emit('updateTime', payload),
});

const timeInputClass = computed(() => 'w-fit cursor-pointer border-0 bg-transparent p-0 text-center shadow-none');
</script>

<template>
    <div class="flex flex-row justify-center gap-x-2">
        <Input
            :class="timeInputClass"
            :disabled="disabled"
            :model-value="localStart"
            type="time"
            @update:model-value="(val) => (localStart = val as string)"
        />
        <Input
            :class="timeInputClass"
            :disabled="disabled"
            :model-value="localEnd"
            type="time"
            @update:model-value="(val) => (localEnd = val as string)"
        />
    </div>
</template>
