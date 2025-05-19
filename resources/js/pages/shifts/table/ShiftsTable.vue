<script lang="ts" setup>
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { columns, ShiftRow } from '@/pages/shifts/table/columns';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import { defineProps } from 'vue';

const props = defineProps<{
    shifts: ShiftRow[];
    canCreateShifts: boolean;
}>();

const table = useVueTable({
    get data() {
        return props.shifts;
    },
    columns,
    getCoreRowModel: getCoreRowModel(),
});

const emit = defineEmits<{
    (e: 'cell-selected', cell: { columnId: string; rowIndex: number }): void;
}>();
</script>

<template>
    <div class="rounded-md border">
        <Table>
            <TableHeader>
                <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                    <TableHead v-for="header in headerGroup.headers" :key="header.id">
                        <FlexRender v-if="!header.isPlaceholder" :props="header.getContext()" :render="header.column.columnDef.header" />
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <template v-if="table.getRowModel().rows?.length">
                    <TableRow
                        v-for="(row, index) in table.getRowModel().rows"
                        :key="row.id"
                        :data-state="row.getIsSelected() ? 'selected' : undefined"
                    >
                        <TableCell
                            v-for="cell in row.getVisibleCells()"
                            :key="cell.id"
                            @click="
                                () => {
                                    if (props.canCreateShifts && !cell.column.id.includes('name')) {
                                        emit('cell-selected', {
                                            columnId: cell.column.id,
                                            rowIndex: index,
                                        });
                                    }
                                }
                            "
                        >
                            {{ cell.getValue() }}
                        </TableCell>
                    </TableRow>
                </template>
            </TableBody>
        </Table>
    </div>
</template>

<style scoped></style>
