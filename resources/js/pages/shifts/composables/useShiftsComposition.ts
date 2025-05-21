// composables/useShifts.ts
import { ShiftData } from '@/pages/shifts/types/ShiftData';
import { isEqual } from 'lodash';
import { computed, ref, toRaw } from 'vue';

export function useShiftsComposition(initial: ShiftData[]) {
    const shifts = ref<ShiftData[]>(structuredClone(toRaw(initial)));

    const hasChanged = computed(() => !isEqual(initial, shifts.value));
    
    const resetShifts = () => {
        shifts.value = structuredClone(toRaw(initial));
    };

    function updateShiftTime(newShift: ShiftData) {
        const shift = shifts.value.find((s) => s.user_id === newShift.user_id);
        if (!shift) return;

        shift.shifts = newShift.shifts;

        shifts.value = shifts.value.map((s) => (s.user_id === shift.user_id ? shift : s));
    }

    return {
        shifts,
        hasChanged,
        resetShifts,
        updateShiftTime,
    };
}
