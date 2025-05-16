<script lang="ts" setup>
import { computed, ref, watch } from 'vue';
import { endOfWeek, format, startOfWeek } from 'date-fns';
import { DateValue, parseDate } from '@internationalized/date';

import { Button } from '@/components/ui/button';
import { CalendarIcon } from 'lucide-vue-next';

const props = defineProps<{
    initialDate?: Date;
}>();


const date = ref<DateValue>(parseDate(props.initialDate?.toISOString().split('T')[0] || new Date().toISOString().split('T')[0]));
watch(() => props.initialDate, (value) => date.value = parseDate(value?.toISOString().split('T')[0] || new Date().toISOString().split('T')[0]));

const weekRange = computed(() => {
    const dateValue = new Date(date.value.toString());
    const start = startOfWeek(dateValue, { weekStartsOn: 1 }); // Week starts on Monday
    const end = endOfWeek(dateValue, { weekStartsOn: 1 });
    return { start, end };
});


const formattedWeekRange = computed(() =>
    `${format(weekRange.value.start, 'MMM d')} - ${format(weekRange.value.end, 'MMM d, yyyy')}`
);

</script>

<template>
    <Button
        :class="[
          'w-[280px] justify-start font-normal',
          !date && 'text-muted-foreground'
        ]"
        variant="outline"
    >
        <CalendarIcon class="mr-2 h-4 w-4" />
        <p class="flex flex-1 justify-center pr-2">
            {{ date ? formattedWeekRange : 'Select week' }}
        </p>
    </Button>

</template>
