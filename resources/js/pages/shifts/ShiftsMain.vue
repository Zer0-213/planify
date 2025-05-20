<script lang="ts" setup>
import { ArrowButton, Button } from '@/components/ui/button';
import { WeekPicker } from '@/components/ui/week-picker';
import AppLayout from '@/layouts/AppLayout.vue';
import { useShiftsComposition } from '@/pages/shifts/composables/useShiftsComposition';
import ShiftEditorDialog from '@/pages/shifts/partials/ShiftEditorDialog.vue';
import { ShiftData } from '@/pages/shifts/types/ShiftData';
import { router, usePage } from '@inertiajs/vue3';
import { format } from 'date-fns';
import { ref } from 'vue';
import ShiftTable from './table/ShiftsTable.vue';

const props = defineProps<{
    shifts: ShiftData[];
    week: string;
}>();
const permissions = usePage().props?.auth?.permissions as string[];
const canCreateShifts = permissions.includes('create_shifts');

const breadcrumbs = [{ title: 'Shift', href: '/shifts' }];

const weekFormatted = ref(new Date(props.week));
const { shifts, selectedShift, hasChanged, resetShifts, updateShift } = useShiftsComposition(props.shifts);

const setWeek = (delta: number) => {
    const newDate = new Date(weekFormatted.value);
    newDate.setDate(newDate.getDate() + delta);
    weekFormatted.value = newDate;

    router.visit('/shifts', {
        data: { week: format(newDate, 'yyyy-MM-dd') },
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8 p-6">
            <div class="flex justify-end">
                <Button :disabled="!hasChanged">Publish</Button>
                <Button :disabled="!hasChanged" class="ml-2" variant="secondary" @click="resetShifts">Cancel</Button>
            </div>

            <div class="flex items-center justify-center gap-4">
                <ArrowButton direction="left" @click="() => setWeek(-7)" />
                <WeekPicker :initial-date="weekFormatted" />
                <ArrowButton direction="right" @click="() => setWeek(7)" />
            </div>

            <ShiftTable :can-create-shifts="canCreateShifts" :shifts="shifts" @cell-selected="(cell) => (selectedShift = cell)" />
            <ShiftEditorDialog v-model:shift="selectedShift" @save="updateShift" />
        </div>
    </AppLayout>
</template>
