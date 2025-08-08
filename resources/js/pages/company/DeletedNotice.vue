<script lang="ts" setup>
import { router } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { Button } from '@/components/ui/button';

defineProps<{
    companyName: string;
}>();

function logout() {
    router.post(route('logout'));
}
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="max-w-md rounded-lg bg-white p-8 text-center shadow-lg">
            <h1 class="mb-4 text-2xl font-bold text-red-600">Company Deleted</h1>
            <p class="mb-6 text-gray-700">
                The company <span class="font-semibold">{{ companyName }}</span> has been deleted. Please contact your administrator if you believe
                this is an error.
            </p>
            <Button variant="destructive" @click="logout">Logout</Button>
        </div>
    </div>
</template>
