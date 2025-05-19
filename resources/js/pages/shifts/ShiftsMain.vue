<script lang="ts" setup>
import { ArrowButton, Button } from '@/components/ui/button';
import { WeekPicker } from '@/components/ui/week-picker';
import AppLayout from '@/layouts/AppLayout.vue';
import { useShifts } from '@/pages/shifts/composables/useShiftsComposition';
import ShiftSection from '@/pages/shifts/partials/ShiftSection.vue';
import { ShiftRow } from '@/pages/shifts/table/columns';
import type { BreadcrumbItem } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { format } from 'date-fns';
import { defineProps, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shift',
        href: '/shifts',
    },
];

const permissions = (usePage().props.auth as { permissions: string[] }).permissions;
const canCreateShifts = permissions?.includes('create_shifts');

const props = defineProps<{
    shifts: ShiftRow[];
    week: string;
}>();

const weekFormatted = ref(new Date(props.week));

const { hasChanged, handleCancel, selectedShift, handleShiftChange } = useShifts(props.shifts);

const emit = defineEmits<{
    (
        e: 'shift-change',
        shift: {
            index: number;
            day: string;
            startTime: string;
            endTime: string;
        },
    ): void;
}>();

const setWeek = (weekAmount: number) => {
    const newDate = new Date(weekFormatted.value);
    newDate.setDate(newDate.getDate() + weekAmount);
    weekFormatted.value = newDate;

    router.visit('/shifts', {
        data: {
            week: format(newDate, 'yyyy-MM-dd'),
        },
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-10 p-6">
            <div v-if="canCreateShifts" class="flex flex-1 justify-end">
                <Button :disabled="hasChanged">Publish</Button>
                <Button :disabled="hasChanged" class="ml-2" variant="secondary" @click="handleCancel">Cancel</Button>
            </div>

            <div class="flex w-full justify-center gap-x-5 pr-4">
                <ArrowButton :on-press="() => setWeek(-7)" direction="left" />
                <WeekPicker :initial-date="weekFormatted" />
                <ArrowButton :on-press="() => setWeek(+7)" />
            </div>
            <ShiftSection :can-create-shifts="canCreateShifts" :shifts="shifts" />
        </div>
    </AppLayout>
</template>

<style scoped></style>
