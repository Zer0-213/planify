import ShiftCell from '@/pages/shifts/table/ShiftCell.vue';
import { Shift } from '@/pages/shifts/types/Shift';
import { ShiftData } from '@/pages/shifts/types/ShiftData';
import { WeekDay } from '@/pages/shifts/types/weekday';
import { createColumnHelper } from '@tanstack/vue-table';
import { h } from 'vue';

const columnHelper = createColumnHelper<ShiftData>();

const WEEKDAYS: WeekDay[] = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

type ColumnOptions = {
    onShiftUpdate?: (userId: number, day: WeekDay, updates: Partial<Shift>) => void;
    canEditShifts?: boolean;
};

export function createShiftColumns(options?: ColumnOptions) {
    return [
        columnHelper.accessor('name', { header: 'Name' }),
        ...WEEKDAYS.map((day) =>
            columnHelper.accessor(() => null, {
                id: day,
                header: day.charAt(0).toUpperCase() + day.slice(1),
                cell: ({ row }) =>
                    h(ShiftCell, {
                        startTime: row.original.shifts[day]?.starts_at || '',
                        endTime: row.original.shifts[day]?.ends_at || '',
                        disabled: !options?.canEditShifts,
                        onUpdateTime: (updates: Partial<Shift>) => options?.onShiftUpdate?.(row.original.user_id, day, updates),
                    }),
            }),
        ),
    ];
}
