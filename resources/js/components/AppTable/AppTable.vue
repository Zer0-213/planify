<script generic="TData" lang="ts" setup>
import { usePageNavigation } from '@/components/AppTable/usePageNavigation';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Paginated } from '@/types/paginated';
import type { ColumnDef } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, getPaginationRowModel, useVueTable } from '@tanstack/vue-table';
import { computed, defineEmits, defineProps } from 'vue';

const props = defineProps<{
    columns: ColumnDef<TData, any>[];
    data: TData[] | Paginated<TData>;
    tableTitle?: string;
    enablePagination?: boolean;
}>();

const emit = defineEmits<{
    (
        e: 'cell-selected',
        cell: {
            columnId: string;
            rowIndex: number;
        },
    ): void;
}>();

const isPaginated = computed(() => {
    return props.enablePagination;
});

const tableData = computed(() => {
    return isPaginated.value ? (props.data as Paginated<TData>).data : (props.data as TData[]);
});

const paginationInfo = computed(() => {
    return isPaginated.value ? (props.data as Paginated<TData>) : null;
});

const table = useVueTable({
    get data() {
        return tableData.value;
    },
    get columns() {
        return props.columns;
    },
    getPaginationRowModel: getPaginationRowModel(),
    getCoreRowModel: getCoreRowModel(),
    manualPagination: isPaginated.value,
    pageCount: paginationInfo.value?.last_page ?? -1,
});

const { goToPage } = usePageNavigation(isPaginated.value, paginationInfo.value);

// Generate page numbers for pagination
const pageNumbers = computed(() => {
    if (!paginationInfo.value) return [];

    const current = paginationInfo.value.current_page;
    const total = paginationInfo.value.last_page;
    const pages = [];

    // Always show first page
    if (total > 0) pages.push(1);

    // Show pages around current page
    const start = Math.max(2, current - 1);
    const end = Math.min(total - 1, current + 1);

    // Add ellipsis if needed
    if (start > 2) pages.push('...');

    // Add middle pages
    for (let i = start; i <= end; i++) {
        if (i !== 1 && i !== total) pages.push(i);
    }

    // Add ellipsis if needed
    if (end < total - 1) pages.push('...');

    // Always show last page (if different from first)
    if (total > 1) pages.push(total);

    return pages;
});
</script>

<template>
    <div class="flex w-full flex-col gap-2">
        <Label v-if="tableTitle" class="justify-start">{{ tableTitle }}</Label>
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
        <div v-if="isPaginated" class="flex items-center justify-center space-x-2 py-4">
            <Button :disabled="!table.getCanPreviousPage()" size="sm" variant="outline" @click="table.previousPage()"> Previous </Button>
            <Button :disabled="!table.getCanNextPage()" size="sm" variant="outline" @click="table.nextPage()"> Next </Button>
        </div>
    </div>
</template>
