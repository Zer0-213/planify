import { StaffProps } from '@/pages/staff/types/staffProps';
import { createColumnHelper } from '@tanstack/vue-table';

const columnHelper = createColumnHelper<StaffProps>();

export const columns = [
    columnHelper.accessor('name', {
        header: 'Name',
    }),
    columnHelper.accessor('email', {
        header: 'Email',
    }),
    columnHelper.accessor('phoneNumber', {
        header: 'Phone Number',
    }),
    columnHelper.accessor('permissions', {
        header: 'Permissions',
        cell: (info) => {
            return info
                .getValue()
                .map((permission) => permission.label)
                .join(', ');
        },
    }),
    columnHelper.accessor('wage', {
        header: 'Wage',
    }),
];
