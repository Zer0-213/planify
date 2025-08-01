import HandleRequestDialog from '@/pages/timeOff/partials/table/HandleRequestDialog.vue';
import { PendingTimeOffRequests } from '@/pages/timeOff/types/PendingTimeOffRequests';
import { formatDateRange } from '@/pages/timeOff/utils/formatDateTimes';
import { createColumnHelper } from '@tanstack/vue-table';
import { format } from 'date-fns';
import { h } from 'vue';

const columnHelper = createColumnHelper<PendingTimeOffRequests>();

export const pendingTimeOffColumns = [
    columnHelper.accessor('company_user.user.name', { header: 'Name' }),
    columnHelper.accessor((row) => formatDateRange(row.start_date, row.end_date), { header: 'Dates' }),
    columnHelper.accessor((row) => format(row.created_at, 'dd/MM/yyyy'), { header: 'Requested At' }),
    columnHelper.accessor('reason', { header: 'Reason' }),
    columnHelper.accessor('approve', {
        header: '',
        cell: () => h(HandleRequestDialog),
    }),
];
