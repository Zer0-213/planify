<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';

const breadcrumbs = [{ title: 'Staff', href: '/staff' }];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-4 text-2xl font-bold">Staff Management</h1>
            <p>Manage your staff members here.</p>
            <!-- Additional content for staff management can be added here -->
        </div>
    </AppLayout>
</template>

<style scoped></style>
