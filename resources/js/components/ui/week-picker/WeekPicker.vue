<script lang="ts" setup>
import { computed, ref } from 'vue';
import { Calendar } from '@/components/ui/calendar';
import { Button } from '@/components/ui/button';
import { CalendarIcon } from 'lucide-vue-next';
import { addDays, endOfWeek, format, startOfWeek } from 'date-fns';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { DateValue } from '@internationalized/date';

const props = defineProps<{
    initialDate?: Date
}>();

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const date = ref((props.initialDate || new Date()));

const weekRange = computed(() => {
    const start = startOfWeek(date.value, { weekStartsOn: 1 }); // Week starts on Monday
    const end = endOfWeek(date.value, { weekStartsOn: 1 });
    return { start, end };
});

const formattedWeekRange = computed(() => {
    return `${format(weekRange.value.start, 'MMM d')} - ${format(
        weekRange.value.end,
        'MMM d, yyyy'
    )}`;
});

const weekDays = computed(() => {
    const days = [];
    let currentDay = weekRange.value.start;

    for (let i = 0; i < 7; i++) {
        days.push(currentDay);
        currentDay = addDays(currentDay, 1);
    }

    return days;
});

const handleSelect = (selectedDate: DateValue | undefined) => {
    if (selectedDate) {
        const dateValue = new Date(selectedDate.toString());
        date.value = dateValue;
        emit('update:modelValue', dateValue);
    }
};

</script>

<template>
    <Popover v-model:open="isOpen">
        <PopoverTrigger as-child>
            <Button
                :class="[
          'w-[280px] justify-start text-left font-normal',
          !props.initialDate && 'text-muted-foreground'
        ]"
                variant="outline"
            >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ props.initialDate ? formattedWeekRange : 'Select week' }}
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0">
            <Calendar
                v-model="date as any"
                class="rounded-md border"
                mode="single"
                @update:model-value="handleSelect"
            >
                <template #day="{ date: dayDate }">
                    <div
                        :class="[
              'h-8 w-8 p-0 font-normal',
              weekDays.some(
                (d) => format(d, 'yyyy-MM-dd') === format(dayDate, 'yyyy-MM-dd')
              ) && 'bg-accent',
              format(weekRange.start, 'yyyy-MM-dd') ===
                format(dayDate, 'yyyy-MM-dd') && 'rounded-l-md',
              format(weekRange.end, 'yyyy-MM-dd') ===
                format(dayDate, 'yyyy-MM-dd') && 'rounded-r-md'
            ]"
                    >
                        {{ format(dayDate, 'd') }}
                    </div>
                </template>
            </Calendar>
        </PopoverContent>
    </Popover>
</template>
