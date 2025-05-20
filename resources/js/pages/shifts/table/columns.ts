import { Shift } from '@/pages/shifts/types/Shift';
import { ShiftData } from '@/pages/shifts/types/ShiftData';
import { WeekDay } from '@/pages/shifts/types/weekday';
import { createColumnHelper } from '@tanstack/vue-table';
import { format } from 'date-fns';

const columnHelper = createColumnHelper<ShiftData>();

const formatShiftTime = (shift: Shift): string => {
    if (!shift?.starts_at || !shift?.ends_at) return '-';
    return `${format(new Date(shift.starts_at), 'HH:mm')} - ${format(new Date(shift.ends_at), 'HH:mm')}`;
};

const WEEKDAYS: WeekDay[] = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

export const columns = [
    columnHelper.accessor('name', {
        header: 'Name',
    }),
    ...WEEKDAYS.map((day) =>
        columnHelper.accessor((row) => formatShiftTime(row.shifts[day]), {
            header: day.charAt(0).toUpperCase() + day.slice(1),
            id: day,
        }),
    ),
];
