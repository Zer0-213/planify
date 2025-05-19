import { createColumnHelper } from '@tanstack/vue-table';

type NullableDate = Date | null;

export type ShiftRow = {
    name: NullableDate;
    monday: NullableDate;
    tuesday: NullableDate;
    wednesday: NullableDate;
    thursday: NullableDate;
    friday: NullableDate;
    saturday: NullableDate;
    sunday: NullableDate;
};

const columnHelper = createColumnHelper<ShiftRow>();

export const columns = [
    columnHelper.accessor('name', {
        header: 'Name',
    }),
    columnHelper.accessor('monday', {
        header: 'Monday',
    }),
    columnHelper.accessor('tuesday', {
        header: 'Tuesday',
    }),
    columnHelper.accessor('wednesday', {
        header: 'Wednesday',
    }),
    columnHelper.accessor('thursday', {
        header: 'Thursday',
    }),
    columnHelper.accessor('friday', {
        header: 'Friday',
    }),
    columnHelper.accessor('saturday', {
        header: 'Saturday',
    }),
    columnHelper.accessor('sunday', {
        header: 'Sunday',
    }),
];
