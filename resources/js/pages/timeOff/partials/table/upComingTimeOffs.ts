import EditDialog from '@/pages/timeOff/partials/table/EditDialog.vue';
import { UserTimeOffRequest } from '@/pages/timeOff/types/userTimeOffRequest';
import { formatDateRange, formatTimeRange } from '@/pages/timeOff/utils/formatDateTimes';
import { createColumnHelper } from '@tanstack/vue-table';
import { format } from 'date-fns';
import { h } from 'vue';

const columnHelper = createColumnHelper<UserTimeOffRequest>();

export const upComingTimeOffs = [
    columnHelper.accessor('company_user.user.name', { header: 'Name' }),
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
                startTime: row.original.start_time || undefined,
                endTime: row.original.end_time || undefined,
                isFullDay: row.original.is_full_day || false,
                currentStatus: row.original.status,
            }),
    }),
];
