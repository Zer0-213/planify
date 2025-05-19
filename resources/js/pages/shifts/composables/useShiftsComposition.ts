import { ShiftRow } from '@/pages/shifts/table/columns';
import { isEqual } from 'lodash';
import { computed, ref } from 'vue';

export function useShifts(initialShifts: ShiftRow[]) {
    const currentShifts = ref<ShiftRow[]>([...initialShifts]);

    const handleShiftChange = (rowIndex: number, columnId: string, value: string) => {
        currentShifts.value[rowIndex][columnId] = value;
    };

    const hasChanged = computed(() => isEqual(initialShifts, currentShifts.value));

    const selectedShift = ref<{ rowIndex: number; columnId: string } | null>(null);

    const handleCancel = () => {
        currentShifts.value = [...initialShifts];
    };

    return {
        hasChanged,
        handleCancel,
        selectedShift,
        handleShiftChange,
    };
}
