<script lang="ts" setup>
import AppTable from '@/components/AppTable.vue';
import { ArrowButton, Button } from '@/components/ui/button';
import { WeekPicker } from '@/components/ui/week-picker';
import AppLayout from '@/layouts/AppLayout.vue';
import { columns, ShiftRow } from '@/pages/shifts/table/columns';
import type { BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shift',
        href: '/shifts',
    },
];

const permissions = (usePage().props.auth as { permissions: string[] }).permissions;

defineProps<{
    shifts: ShiftRow[];
}>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-10 p-6">
            <div v-if="permissions?.includes('create_shifts')" class="flex flex-1 justify-end">
                <Button>Publish</Button>
            </div>

            <div class="flex w-full justify-center space-x-2">
                <ArrowButton direction="left" />
                <WeekPicker />
                <ArrowButton />
            </div>

            <AppTable :columns="columns" :data="shifts" />
        </div>
    </AppLayout>
</template>

<style scoped></style>
