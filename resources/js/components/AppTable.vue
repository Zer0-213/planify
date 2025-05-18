<script generic="TData, TValue" lang="ts" setup>
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { UpdateTable } from '@/types/updateTable';
import type { ColumnDef } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';

const props = defineProps<{
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    isEditable?: boolean;
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
    (e: 'update-cell', data: UpdateTable): void;
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
                    <TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() ? 'selected' : undefined">
                        <TableCell
                            v-for="cell in row.getVisibleCells()"
                            :key="cell.id"
                            :contenteditable="isEditable ?? false"
                            @input="
                                (event: Event) => {
                                    const target = event.target as HTMLTableCellElement;
                                    const newValue = target.innerText;
                                    const columnId = cell.column.id;
                                    const rowIndex = cell.row.index;

                                    emit('update-cell', {
                                        columnId,
                                        value: newValue,
                                        rowIndex,
                                    });
                                }
                            "
                        >
                            {{ cell.getValue() }}
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
</template>
