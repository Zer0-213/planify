<script generic="TData" lang="ts" setup>
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import type { ColumnDef } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import { defineEmits, defineProps } from 'vue';

const props = defineProps<{
    columns: ColumnDef<TData, any>[];
    data: TData[];
    tableTitle?: string;
}>();

const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
});

const emit = defineEmits<{
    (
        e: 'cell-selected',
        cell: {
            columnId: string;
            rowIndex: number;
        },
    ): void;
}>();
</script>

<template>
    <div class="flex w-full flex-col gap-2">
        <Label v-if="tableTitle" class="justify-center">{{ tableTitle }}</Label>
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
                                        emit('cell-selected', {
                                            columnId: cell.column.id,
                                            rowIndex: index,
                                        });
                                    }
                                "
                            >
                                <FlexRender :props="cell.getContext()" :render="cell.column.columnDef.cell" />
                            </TableCell>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell :colspan="columns.length" class="h-24 text-center"> No results.</TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
