// resources/js/pages/shifts/table/columns.ts
import ShiftCell from '@/pages/shifts/table/ShiftCell.vue';
import { ShiftTime, UserShift, WeekDay } from '@/pages/shifts/types/shiftTypes';
import { createColumnHelper } from '@tanstack/vue-table';
import { format, parseISO } from 'date-fns';
import { h } from 'vue';

const columnHelper = createColumnHelper<UserShift>();

const WEEKDAYS: WeekDay[] = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

type ColumnOptions = {
    onShiftUpdate?: (userId: number, updates: Partial<ShiftTime>) => void;
    canEditShifts?: boolean;
    weekStartDate?: string;
};

export function createShiftColumns(options?: ColumnOptions) {
    return [
        columnHelper.accessor('name', { header: 'Name' }),
        ...WEEKDAYS.map((day, index) =>
            columnHelper.accessor(() => null, {
                id: day,
                header: day.charAt(0).toUpperCase() + day.slice(1),
                cell: ({ row }) => {
                    const weekStartDate = options?.weekStartDate ? parseISO(options.weekStartDate) : new Date();
                    const columnDate = new Date(weekStartDate);
                    columnDate.setDate(weekStartDate.getDate() + index);
                    const formattedDate = format(columnDate, 'yyyy-MM-dd');

                    const dayShift = row.original.shifts.find((shift) => {
                        if (!shift.starts_at) return false;
                        const shiftDate = format(parseISO(shift.starts_at), 'yyyy-MM-dd');
                        return shiftDate === formattedDate;
                    });

                    return h(ShiftCell, {
                        startTime: dayShift?.starts_at || '',
                        endTime: dayShift?.ends_at || '',
                        date: formattedDate,
                        disabled: !options?.canEditShifts,
                        onUpdateTime: (updates: Partial<ShiftTime>) => {
                            options?.onShiftUpdate?.(row.original.user_id, {
                                ...dayShift,
                                ...updates,
                            });
                        },
                    });
                },
            }),
        ),
        columnHelper.accessor((row) => `${row.total_hours}:${row.total_minutes}`, {
            header: 'Total Hours',
        }),
    ];
}
