// resources/js/pages/shifts/composables/useShiftsComposition.ts
import { UserShift } from '@/pages/shifts/types/shiftTypes';
import { formatShiftTime } from '@/pages/shifts/utils/shiftTimeUtils';
import { isEqual } from 'lodash';
import { computed, ref, toRaw } from 'vue';

export function useShiftsComposition(initial: UserShift[]) {
    const normalizeShiftData = (shiftData: UserShift): UserShift => ({
        ...shiftData,
        shifts: shiftData.shifts.map((shift) => ({
            ...shift,
            id: shift.id ?? null,
            shift_date: shift.shift_date,
            starts_at: formatShiftTime(shift.starts_at),
            ends_at: formatShiftTime(shift.ends_at),
        })),
    });

    const shifts = ref<UserShift[]>(initial.map(normalizeShiftData));
    const originalShifts = ref<UserShift[]>(structuredClone(toRaw(shifts.value)));

    const hasChanged = computed(() => !isEqual(originalShifts.value, shifts.value));

    const resetShifts = () => {
        shifts.value = structuredClone(toRaw(originalShifts.value));
    };

    function updateShift(updatedShift: UserShift) {
        shifts.value = shifts.value.map((shift) => (shift.user_id === updatedShift.user_id ? updatedShift : shift));
    }

    return {
        shifts,
        hasChanged,
        resetShifts,
        updateShift,
    };
}
