<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import ShiftsTable from '@/pages/shifts/table/ShiftsTable.vue';
import { ShiftData } from '@/pages/shifts/types/ShiftData';
import { UpdateShift } from '@/pages/shifts/types/updateShift';
import { ref, watch } from 'vue';

defineProps<{
    canCreateShifts: boolean;
    shifts: ShiftData[];
}>();

const emit = defineEmits<{
    (e: 'update-selected-shift', value: UpdateShift): void;
}>();

const selectedShift = ref<any>(null);
const startTime = ref('08:00');
const endTime = ref('17:00');

watch(selectedShift, (val) => {
    if (val) {
        startTime.value = '08:00';
        endTime.value = '17:00';
    }
});
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
                        <Input v-model="startTime" type="time" />
                    </div>
                    <div class="flex flex-col gap-y-1">
                        <Label>End Time</Label>
                        <Input v-model="endTime" type="time" />
                    </div>
                </div>

                <DialogFooter class="sm:justify-start">
                    <Button
                        class="w-full"
                        type="button"
                        @click="
                            () => {
                                emit('update-selected-shift', {
                                    userIndex: selectedShift.rowIndex,
                                    day: selectedShift.columnId,
                                    start: startTime,
                                    end: endTime,
                                });
                                selectedShift = null;
                            }
                        "
                        >Save
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
