<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps<{
    isOwner: boolean;
}>();
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <Card class="p- flex flex-col items-center p-10">
            <h1 class="mb-4 rounded-2xl text-2xl font-bold">Subscription Required</h1>

            <p class="mb-4">Your company’s subscription is currently inactive.</p>

            <div v-if="isOwner" class="text-center">
                <p class="mb-4">As the company owner, you can manage your subscription.</p>
                <Button as-child>
                    <Link :href="route('billing.portal')">Go to Billing Portal</Link>
                </Button>
            </div>

            <div v-else>
                <p>Please contact your company administrator to resolve this issue.</p>
            </div>
        </Card>
    </div>
</template>

<style scoped></style>
