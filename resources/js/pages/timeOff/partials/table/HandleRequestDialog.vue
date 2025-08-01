<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { PendingTimeOffRequests } from '@/pages/timeOff/types/PendingTimeOffRequests';
import { defineProps } from 'vue';

type Props = PendingTimeOffRequests;

defineProps<Props>();
</script>

<template>
    <div class="flex justify-center gap-4">
        <Button class="text-blue-500" variant="ghost">Approve</Button>
        <Button class="text-red-500" variant="ghost">Reject</Button>
    </div>
</template>

<style scoped></style>
