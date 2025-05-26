<script lang="ts" setup>
import AppTable from '@/components/AppTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AddStaffDialog from '@/pages/staff/partials/AddStaffDialog.vue';
import { columns } from '@/pages/staff/table/columns';
import { StaffProps } from '@/pages/staff/types/staffProps';
import { usePage } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const breadcrumbs = [{ title: 'Staff', href: '/staff' }];

const props = defineProps<{
    staffMembers: StaffProps[];
}>();

const permissions = usePage().props?.auth?.permissions as string[];
const canCreateStaff = permissions.includes('create_staff');

const { staffMembers } = props;
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-4 text-2xl font-bold">Staff Management</h1>
            <p>Manage your staff members here.</p>
            <section class="mt-6 w-full">
                <AddStaffDialog v-if="canCreateStaff" />
                <AppTable :columns="columns" :data="staffMembers" table-title="Staff List" />
            </section>
        </div>
    </AppLayout>
</template>

<style scoped></style>
