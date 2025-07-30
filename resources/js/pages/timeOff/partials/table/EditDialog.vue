<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { defineProps, ref } from 'vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    id: number;
    startDate: string;
    endDate: string;
    startTime?: string;
    endTime?: string;
    isFullDay: boolean;
}>();

const localTimeOff = ref({ ...props });

const form = useForm({
    ...localTimeOff.value,
});
</script>

<template>
    <Dialog>
        <DialogTrigger class="cursor-pointer text-blue-500 underline">Edit</DialogTrigger>

        <DialogContent>
            <DialogTitle>Edit</DialogTitle>
            <DialogDescription>Edit Time Off Request</DialogDescription>

            <form :form="form" class="space-y-6" @submit.prevent="">
                <!-- Full Day Checkbox -->
                <div class="flex items-center gap-2">
                    <input id="full_day" v-model="localTimeOff.isFullDay" class="accent-blue-600" type="checkbox" />
                    <Label for="full_day">Full Day</Label>
                </div>

                <Label for="start_date">Start Date</Label>
                <Input id="start_date" v-model="localTimeOff.startDate" type="date" />

                <Label for="end_date">End Date</Label>
                <Input id="end_date" v-model="localTimeOff.endDate" type="date" />

                <DialogFooter class="justify-end, mt-4 flex flex-row gap-2">
                    <Button>Submit</Button>
                    <Button variant="destructive">Delete</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

<style scoped></style>
