import type { ColumnDef } from '@tanstack/vue-table';

type NullableString = string | null;

export type ShiftRow = {
    name: NullableString;
    monday: NullableString;
    tuesday: NullableString;
    wednesday: NullableString;
    thursday: NullableString;
    friday: NullableString;
    saturday: NullableString;
    sunday: NullableString;
};

export const shiftColumns: ColumnDef<ShiftRow>[] = [
    {
        accessorKey: 'name',
        header: 'Name',
    },
    {
        accessorKey: 'monday',
        header: 'Monday',
    },
    {
        accessorKey: 'tuesday',
        header: 'Tuesday',
    },
    {
        accessorKey: 'wednesday',
        header: 'Wednesday',
    },
    {
        accessorKey: 'thursday',
        header: 'Thursday',
    },
    {
        accessorKey: 'friday',
        header: 'Friday',
    },
    {
        accessorKey: 'saturday',
        header: 'Saturday',
    },
    {
        accessorKey: 'sunday',
        header: 'Sunday',
    },
];
