<script lang="ts" setup>
import { Input } from '@/components/ui/input';
import { useShiftTime } from '@/pages/shifts/composables/useShiftTime';
import { TimeUpdatePayload } from '@/pages/shifts/types/timeUpdate';
import { computed } from 'vue';

type Props = {
    startTime: string;
    endTime: string;
    disabled?: boolean;
};

const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'updateTime', payload: TimeUpdatePayload): void;
}>();

const { localStart, localEnd } = useShiftTime(props.startTime, props.endTime, props.disabled, (payload) => emit('updateTime', payload));

const inputClass = computed(() => 'w-fit cursor-pointer border-0 bg-transparent p-0 text-center shadow-none');
</script>

<template>
    <div class="flex flex-row justify-center gap-x-2">
        <Input
            :class="inputClass"
            :disabled="disabled"
            :model-value="localStart"
            type="time"
            @update:model-value="(val) => (localStart = val as string)"
        />
        <Input
            :class="inputClass"
            :disabled="disabled"
            :model-value="localEnd"
            type="time"
            @update:model-value="(val) => (localEnd = val as string)"
        />
    </div>
</template>
