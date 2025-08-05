<script lang="ts" setup>
import AppTable from '@/components/AppTable/AppTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AddStaffDialog from '@/pages/staff/partials/AddStaffDialog.vue';
import { createColumns } from '@/pages/staff/table/columns';
import { StaffProps } from '@/pages/staff/types/staffProps';
import { Role } from '@/types/role';
import { usePage } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const breadcrumbs = [{ title: 'Staff', href: '/staff' }];

defineProps<{
    staffMembers: StaffProps[];
    roles: Role[];
}>();

const permissions = usePage().props?.auth?.permissions as string[];
const user = usePage().props?.auth?.user;

const canCreateStaff = permissions.includes('create_user');
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-4 text-2xl font-bold">Staff List</h1>
            <section class="mt-6 w-full">
                <AddStaffDialog v-if="canCreateStaff" :roles="roles" />
                <AppTable
                    :columns="createColumns(user?.id, permissions.includes('view_all_wages'), roles)"
                    :data="staffMembers"
                    table-title="Staff List"
                />
            </section>
        </div>
    </AppLayout>
</template>

<style scoped></style>
