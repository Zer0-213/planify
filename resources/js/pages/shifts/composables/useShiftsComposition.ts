import { ShiftRow } from '@/pages/shifts/table/columns';
import { UpdateTable } from '@/types/updateTable';
import { isEqual } from 'lodash';
import { computed, ref } from 'vue';

export function useShifts(initialShifts: ShiftRow[]) {
    const currentShifts = ref<ShiftRow[]>([...initialShifts]);

    const hasChanged = computed(() => isEqual(initialShifts, currentShifts.value));

    const handleCellUpdate = (newData: UpdateTable) => {
        const { rowIndex, columnId, value } = newData;
        const updatedShifts = [...currentShifts.value];
        updatedShifts[rowIndex] = {
            ...updatedShifts[rowIndex],
            [columnId]: value?.trim(),
        };
        currentShifts.value = updatedShifts;
    };

    const handleCancel = () => {
        currentShifts.value = [...initialShifts];
    };

    return {
        hasChanged,
        handleCellUpdate,
        handleCancel,
    };
}
