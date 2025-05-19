<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import ShiftsTable from '@/pages/shifts/table/ShiftsTable.vue';
import type { ShiftRow } from '@/pages/shifts/table/columns';
import { ref, watch } from 'vue';

defineProps<{
    canCreateShifts: boolean;
    shifts: ShiftRow[];
}>();

const emit = defineEmits<{
    (e: 'update:selectedShift', value: any): void;
}>();

const selectedShift = ref<any>(null);

watch(selectedShift, (val) => emit('update:selectedShift', val));
</script>

<template>
    <div>
        <ShiftsTable :can-create-shifts="canCreateShifts" :shifts="shifts" @cell-selected="(cell) => (selectedShift = cell)" />

        <Dialog :open="selectedShift !== null">
            <DialogContent :on-close="() => (selectedShift = null)" @close="selectedShift = null">
                <DialogHeader>
                    <DialogTitle>Edit Shift</DialogTitle>
                </DialogHeader>

                <div class="flex flex-col gap-y-2">
                    <div class="mb-1 flex flex-col gap-y-1">
                        <span class="font-medium">{{ selectedShift?.columnId.toUpperCase() }}</span>
                    </div>
                    <div class="flex flex-col gap-y-1">
                        <Label>Start Time</Label>
                        <Input type="time" />
                    </div>
                    <div class="flex flex-col gap-y-1">
                        <Label>End Time</Label>
                        <Input type="time" />
                    </div>
                </div>

                <DialogFooter class="sm:justify-start">
                    <Button class="w-full" type="button" @click="() => {}">Save</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
