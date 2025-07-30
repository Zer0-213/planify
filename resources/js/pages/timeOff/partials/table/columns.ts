import EditDialog from '@/pages/timeOff/partials/table/EditDialog.vue';
import { UserTimeOffRequest } from '@/pages/timeOff/types/userTimeOffRequest';
import { createColumnHelper } from '@tanstack/vue-table';
import { format, parseISO } from 'date-fns';
import { h } from 'vue';

const columnHelper = createColumnHelper<UserTimeOffRequest>();

const formatDateRange = (start: string | null, end: string | null) =>
    start && end ? `${format(start, 'dd/MM/yyyy')} - ${format(end, 'dd/MM/yyyy')}` : 'N/A';

const formatTimeRange = (start: string | null, end: string | null) => {
    if (!start || !end) return 'N/A';

    if (start.includes(':') && start.length <= 8) {
        const startTime = start.substring(0, 5); // Get HH:MM part
        const endTime = end.substring(0, 5); // Get HH:MM part
        return `${startTime} - ${endTime}`;
    }

    // If it's ISO format, pare and format
    try {
        const startFormatted = format(parseISO(start), 'HH:mm');
        const endFormatted = format(parseISO(end), 'HH:mm');
        return `${startFormatted} - ${endFormatted}`;
    } catch {
        return `${start} - ${end}`;
    }
};

export const columns = [
    columnHelper.accessor((row) => formatDateRange(row.start_date, row.end_date), { header: 'Dates' }),
    columnHelper.accessor((row) => formatTimeRange(row.start_time, row.end_time), { header: 'Time' }),
    columnHelper.accessor((row) => format(row.created_at, 'dd/MM/yyyy'), { header: 'Requested At' }),
    columnHelper.accessor('status', { header: 'Status' }),
    // columnHelper.accessor(''),
    columnHelper.accessor('reason', { header: 'Reason' }),
    // columnHelper.accessor('admin_notes', {}),
    columnHelper.accessor('edit', {
        header: 'Actions',
        cell: ({ row }) =>
            h(EditDialog, {
                id: row.original.id,
                startDate: row.original.start_date,
                endDate: row.original.end_date,
                //startTime: info.row.original.start_time,
                //endTime: info.row.original.end_time,
                isFullDay: row.original.is_full_day || false,
            }),
    }),
];
