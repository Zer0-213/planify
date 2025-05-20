<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { UpdateShift } from '@/pages/shifts/types/updateShift';
import { ref, watch } from 'vue';

const model = defineModel<{ rowIndex: number; columnId: string } | null>('shift');
const emit = defineEmits<{ (e: 'save', value: UpdateShift): void }>();

const startTime = ref('08:00');
const endTime = ref('17:00');

watch(model, (val) => {
    if (val) {
        startTime.value = '08:00';
        endTime.value = '17:00';
    }
});
</script>

<template>
    <Dialog :open="model !== null">
        <DialogContent :on-close="() => (model = null)">
            <DialogHeader>
                <DialogTitle>Edit Shift</DialogTitle>
                <DialogDescription></DialogDescription>
            </DialogHeader>

            <div class="space-y-3">
                <div>
                    <Label>Start Time</Label>
                    <Input v-model="startTime" type="time" />
                </div>
                <div>
                    <Label>End Time</Label>
                    <Input v-model="endTime" type="time" />
                </div>
            </div>

            <DialogFooter>
                <Button
                    class="w-full"
                    @click="
                        () => {
                            if (model) {
                                emit('save', {
                                    userIndex: model.rowIndex,
                                    day: model.columnId,
                                    start: startTime,
                                    end: endTime,
                                });
                                model = null;
                            }
                        }
                    "
                >
                    Save
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
