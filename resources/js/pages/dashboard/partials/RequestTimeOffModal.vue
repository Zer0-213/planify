<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const form = useForm({
    start_date: format(new Date(), 'yyyy-MM-dd'),
    end_date: format(new Date(), 'yyyy-MM-dd'),
    start_time: '00:00',
    end_time: '23:59',
});

const isFullDay = ref(true);

const replaceDashes = (date: string) => format(parseISO(date), 'dd/MM/yyyy');

const handleSubmit = () => {
    form.post(route('time-off-requests.store'), {
        onSuccess: () => {
            form.reset();
            isFullDay.value = true;
            toast.success('Time off request submitted successfully');
        },
        onError: (error) => {
            console.log(error);
            toast.error(error.error);
        },
    });
};

</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button class="rounded-xl bg-blue-600 px-5 py-2 text-white hover:bg-blue-700"> Request Time Off</Button>
        </DialogTrigger>
        <DialogContent>
            <DialogTitle>Request Time Off</DialogTitle>
            <DialogDescription>Submit a request for one or more days off</DialogDescription>

            <form class="space-y-6" @submit.prevent="handleSubmit">
                <!-- Full Day Checkbox -->
                <div class="flex items-center gap-2">
                    <input id="full_day" v-model="isFullDay" class="accent-blue-600" type="checkbox" />
                    <Label for="full_day">Full Day</Label>
                </div>

                <!-- Start Date and Time -->
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-3">
                        <Label for="start_date">Start Date</Label>
                        <Input id="start_date" v-model="form.start_date" type="date" />
                        <Label v-if="!isFullDay" for="start_time">Start Time</Label>
                        <Input v-if="!isFullDay" id="start_time" v-model="form.start_time" class="disabled:opacity-50" type="time" />
                    </div>
                    <p v-if="!isFullDay" class="ml-1 text-sm text-gray-500">Start Time applies only to the first day.</p>
                </div>

                <!-- End Date and Time -->
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-3">
                        <Label for="end_date">End Date</Label>
                        <Input id="end_date" v-model="form.end_date" type="date" />
                        <Label v-if="!isFullDay" for="end_time">End Time</Label>
                        <Input v-if="!isFullDay" id="end_time" v-model="form.end_time" class="disabled:opacity-50" type="time" />
                    </div>
                    <p v-if="!isFullDay" class="ml-1 text-sm text-gray-500">End Time applies only to the last day.</p>
                </div>

                <!-- Summary (Optional Display) -->
                <div class="text-sm text-gray-600">
                    <span>You’re requesting time off from </span>
                    <strong>{{ replaceDashes(form.start_date) }}</strong>
                    <span v-if="!isFullDay"> at {{ form.start_time }}</span>
                    <span> to </span>
                    <strong>{{ replaceDashes(form.end_date) }}</strong>
                    <span v-if="!isFullDay"> at {{ form.end_time }}</span
                    >.
                </div>

                <DialogFooter>
                    <Button type="submit">Submit Request</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
