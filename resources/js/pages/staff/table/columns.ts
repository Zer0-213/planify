import EditButton from '@/pages/staff/table/EditButton.vue';
import { StaffProps } from '@/pages/staff/types/staffProps';
import { Role } from '@/types/role';
import { createColumnHelper } from '@tanstack/vue-table';
import { h } from 'vue';

const columnHelper = createColumnHelper<StaffProps>();

export const createColumns = (currentUserId: number, hasWagePermission: boolean, roles: Role[]) => {
    return [
        columnHelper.accessor('name', {
            header: 'Name',
        }),
        columnHelper.accessor('email', {
            header: 'Email',
        }),
        columnHelper.accessor('phoneNumber', {
            header: 'Phone Number',
        }),
        columnHelper.accessor('role', {
            header: 'Role',
        }),
        columnHelper.accessor('wage', {
            header: 'Wage',
            cell: ({ row }) => {
                return hasWagePermission || row.original.id === currentUserId ? row.original.wage : '—';
            },
        }),
        columnHelper.accessor('edit', {
            header: ' ',
            cell: ({ row }) => {
                return h(EditButton, {
                    staffId: row.original.id,
                    staffName: row.original.name,
                    wage: row.original.wage,
                    roles: roles,
                    currentRole: roles[roles.findIndex((r) => r.name === row.original.role)],
                });
            },
        }),
    ];
};
