<script lang="ts" setup>
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { createShiftColumns } from '@/pages/shifts/table/columns';
import { Shift } from '@/pages/shifts/types/Shift';
import { ShiftData } from '@/pages/shifts/types/ShiftData';
import { WeekDay } from '@/pages/shifts/types/weekday';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import { defineProps } from 'vue';

const props = defineProps<{
    shifts: ShiftData[];
    canCreateShifts: boolean;
}>();

const emit = defineEmits<{
    (e: 'update-shift', updated: ShiftData): void;
}>();

function handleShiftUpdate(userId: number, day: WeekDay, updates: Partial<Shift>) {
    const row = props.shifts.find((shift) => shift.user_id === userId);
    if (!row) return;

    const updated: ShiftData = {
        ...row,
        shifts: {
            ...row.shifts,
            [day]: {
                ...row.shifts[day],
                ...updates,
            },
        },
    };

    emit('update-shift', updated);
}

const columns = createShiftColumns({ onShiftUpdate: handleShiftUpdate, canEditShifts: props.canCreateShifts });

const table = useVueTable({
    get data() {
        return props.shifts;
    },
    columns,
    getCoreRowModel: getCoreRowModel(),
});
</script>

<template>
    <div class="rounded-md border">
        <Table>
            <TableHeader>
                <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                    <TableHead v-for="header in headerGroup.headers" :key="header.id" class="text-center">
                        <FlexRender v-if="!header.isPlaceholder" :props="header.getContext()" :render="header.column.columnDef.header" />
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <template v-if="table.getRowModel().rows?.length">
                    <TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() ? 'selected' : undefined">
                        <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                            <FlexRender :props="cell.getContext()" :render="cell.column.columnDef.cell" />
                        </TableCell>
                    </TableRow>
                </template>
            </TableBody>
        </Table>
    </div>
</template>

<style scoped></style>
