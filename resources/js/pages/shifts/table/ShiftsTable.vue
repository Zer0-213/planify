<script lang="ts" setup>
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { createShiftColumns } from '@/pages/shifts/table/columns';
import { ShiftTime, UserShift } from '@/pages/shifts/types/shiftTypes';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';

const props = defineProps<{
    shifts: UserShift[];
    canCreateShifts: boolean;
    weekStartDate: string;
}>();

const emit = defineEmits<{
    (e: 'update-shift', updated: UserShift): void;
}>();

function handleShiftUpdate(userId: number, updates: Partial<ShiftTime>) {
    const user = props.shifts.find((shift) => shift.user_id === userId);
    if (!user) return;

    const existingShiftIndex = user.shifts.findIndex((shift) => shift.id === updates?.id);
    const updatedShifts = [...user.shifts];

    if (existingShiftIndex >= 0) {
        updatedShifts[existingShiftIndex] = {
            ...updatedShifts[existingShiftIndex],
            ...updates,
        };
    } else {
        updatedShifts.push({
            id: updates?.id || null,
            starts_at: updates?.starts_at || '',
            ends_at: updates?.ends_at || '',
            shift_date: updates?.shift_date || '',
        });
    }

    emit('update-shift', {
        ...user,
        shifts: updatedShifts,
    });
}

const columns = createShiftColumns({
    onShiftUpdate: handleShiftUpdate,
    canEditShifts: props.canCreateShifts,
    weekStartDate: props.weekStartDate,
});

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
