<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useDateTimeFormatting } from '@/pages/timeOff/composables/useDateTimeFormatting';
import { TimeOffStatus } from '@/pages/timeOff/types/timeOffStatus';
import { useForm } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns';
import { computed, defineProps } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    id: number;
    startDate: string;
    endDate: string;
    startTime?: string | null;
    endTime?: string | null;
    isFullDay: boolean;
    currentStatus: TimeOffStatus;
}>();

const { formatTimeForInput, formatDateForInput } = useDateTimeFormatting();

const form = useForm({
    id: props.id,
    start_time: formatTimeForInput(props.startTime),
    end_time: formatTimeForInput(props.endTime),
    start_date: formatDateForInput(props.startDate),
    end_date: formatDateForInput(props.endDate),
    is_full_day: props.isFullDay,
    status: props.currentStatus,
});

const hasChanges = computed(() => {
    return (
        form.start_date !== formatDateForInput(props.startDate) ||
        form.end_date !== formatDateForInput(props.endDate) ||
        form.start_time !== formatTimeForInput(props.startTime) ||
        form.end_time !== formatTimeForInput(props.endTime) ||
        form.is_full_day !== props.isFullDay ||
        form.status !== props.currentStatus
    );
});

const replaceDashes = (date: string) => format(parseISO(date), 'dd/MM/yyyy');

const handleSubmit = () => {
    form.put(route('time-off-requests.update', props.id), {
        onSuccess: () => {
            toast.success('Time off request updated successfully');
        },
        onError: (error) => {
            console.log(error);
            toast.error('There was an error updating your time off request.');
        },
        onFinish: () => {
            form.reset();
        },
    });
};

const deleteRequest = () => {
    form.delete(route('time-off-requests.delete', props.id), {
        onSuccess: () => {
            toast.success('Time off request deleted successfully');
        },
        onError: (error) => {
            console.log(error);
            toast.error('There was an error deleting your time off request.');
        },
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Dialog>
        <DialogTrigger class="cursor-pointer text-blue-500 underline">Edit</DialogTrigger>

        <DialogContent>
            <DialogTitle>Edit</DialogTitle>
            <DialogDescription>Edit Time Off Request</DialogDescription>

            <form :form="form" class="space-y-6" @submit.prevent="handleSubmit">
                <!-- Full Day Checkbox -->
                <div class="flex items-center gap-2">
                    <input id="full_day" v-model="form.is_full_day" class="accent-blue-600" type="checkbox" />
                    <Label for="full_day">Full Day</Label>
                </div>

                <!-- Start Date and Time -->
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-3">
                        <Label for="start_date">Start Date</Label>
                        <Input id="start_date" v-model="form.start_date" type="date" />
                        <Label v-if="!form.is_full_day" for="start_time">Start Time</Label>
                        <Input v-if="!form.is_full_day" id="start_time" v-model="form.start_time" class="disabled:opacity-50" type="time" />
                    </div>
                    <p v-if="!form.is_full_day" class="ml-1 text-sm text-gray-500">Start Time applies only to the first day.</p>
                </div>

                <!-- End Date and Time -->
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-3">
                        <Label for="end_date">End Date</Label>
                        <Input id="end_date" v-model="form.end_date" type="date" />
                        <Label v-if="!form.is_full_day" for="end_time">End Time</Label>
                        <Input v-if="!form.is_full_day" id="end_time" v-model="form.end_time" class="disabled:opacity-50" type="time" />
                    </div>
                    <p v-if="!form.is_full_day" class="ml-1 text-sm text-gray-500">End Time applies only to the last day.</p>
                </div>

                <!-- Summary (Optional Display) -->
                <div class="text-sm text-gray-600">
                    <span>You're requesting time off from </span>
                    <strong>{{ replaceDashes(form.start_date) }}</strong>
                    <span v-if="!form.is_full_day"> at {{ form.start_time }}</span>
                    <span> to </span>
                    <strong>{{ replaceDashes(form.end_date) }}</strong>
                    <span v-if="!form.is_full_day"> at {{ form.end_time }}</span
                    >.
                </div>

                <DialogFooter class="justify-end, mt-4 flex flex-row gap-2">
                    <Button :disabled="!hasChanges" type="submit">Submit</Button>
                    <Button type="button" variant="destructive" @click="deleteRequest">
                        {{ currentStatus === 'pending' ? 'Delete' : 'Request cancellation' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

<style scoped></style>
