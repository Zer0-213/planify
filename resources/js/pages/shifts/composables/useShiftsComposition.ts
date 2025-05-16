import { ShiftRow } from '@/pages/shifts/table/columns';
import { isEqual } from 'lodash';
import { computed, ref } from 'vue';

export function useShifts(initialShifts: ShiftRow[]) {
    const currentShifts = ref<ShiftRow[]>([...initialShifts]);

    const hasChanged = computed(() => isEqual(initialShifts, currentShifts.value));

    const handleCellUpdate = ({ rowIndex, columnId, value }: { rowIndex: number; columnId: string; value: string }) => {
        const updatedShifts = [...currentShifts.value];
        updatedShifts[rowIndex] = {
            ...updatedShifts[rowIndex],
            [columnId]: value,
        };
        currentShifts.value = updatedShifts;
    };

    return {
        currentShifts,
        hasChanged,
        handleCellUpdate,
    };
}
