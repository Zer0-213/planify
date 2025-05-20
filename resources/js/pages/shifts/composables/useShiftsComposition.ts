// composables/useShifts.ts
import { ShiftData } from '@/pages/shifts/types/ShiftData';
import { UpdateShift } from '@/pages/shifts/types/updateShift';
import { WeekDay } from '@/pages/shifts/types/weekday';
import { isEqual } from 'lodash';
import { computed, ref, toRaw } from 'vue';

export function useShiftsComposition(initial: ShiftData[]) {
    const shifts = ref<ShiftData[]>(structuredClone(toRaw(initial)));
    const selectedShift = ref<{ rowIndex: number; columnId: string } | null>(null);

    const hasChanged = computed(() => !isEqual(initial, shifts.value));

    const resetShifts = () => {
        shifts.value = structuredClone(toRaw(initial));
    };

    const updateShift = (update: UpdateShift) => {
        const { userIndex, day, start, end } = update;
        const dayKey = day.toLowerCase() as WeekDay;

        const existingShift = shifts.value[userIndex].shifts[dayKey];

        if (!existingShift || !start || !end) return;

        shifts.value[userIndex].shifts[dayKey] = {
            ...existingShift,
            starts_at: combineDateTime(existingShift.date, start),
            ends_at: combineDateTime(existingShift.date, end),
        };

        shifts.value = [...shifts.value];
    };

    const combineDateTime = (dateStr: string, timeStr: string): string => {
        const [h, m] = timeStr.split(':').map(Number);
        const date = new Date(dateStr);
        date.setHours(h, m, 0, 0);
        return date.toISOString();
    };

    return {
        shifts,
        selectedShift,
        hasChanged,
        resetShifts,
        updateShift,
    };
}
