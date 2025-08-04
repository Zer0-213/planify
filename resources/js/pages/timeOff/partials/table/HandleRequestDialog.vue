<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { PendingTimeOffRequests } from '@/pages/timeOff/types/PendingTimeOffRequests';
import { router } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { toast } from 'vue-sonner';

type Props = PendingTimeOffRequests;

const props = defineProps<Props>();

const handlePress = (action: 'approved' | 'rejected') => {
    router.patch(
        route('time-off-requests.respond', props.id),
        {
            status: action,
        },
        {
            onSuccess: () => router.reload(),
            onError: (e) => {
                console.error(e);
                toast.error('Something went wrong');
            },
        },
    );
};
</script>

<template>
    <div class="flex justify-center gap-4">
        <Button class="text-blue-500" variant="ghost" @click="() => handlePress('approved')">Approve</Button>
        <Button class="text-red-500" variant="ghost" @click="() => handlePress('rejected')">Reject</Button>
    </div>
</template>

<style scoped></style>
