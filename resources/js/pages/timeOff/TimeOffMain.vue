<script lang="ts" setup>
import AppTable from '@/components/AppTable.vue';
import { PermissionEnum } from '@/enums/permissionEnum';
import AppLayout from '@/layouts/AppLayout.vue';
import { currentUserTimeOffColumns } from '@/pages/timeOff/partials/table/currentUserTimeOffColumns';
import { pendingTimeOffColumns } from '@/pages/timeOff/partials/table/pendingTimeOffColumns';
import { PendingTimeOffRequests } from '@/pages/timeOff/types/PendingTimeOffRequests';
import { UserTimeOffRequest } from '@/pages/timeOff/types/userTimeOffRequest';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { defineProps } from 'vue';

type PageProps = {
    userTimeOff: Array<UserTimeOffRequest>;
    pendingRequests: Array<PendingTimeOffRequests> | null;
};

const permissions = usePage<SharedData>().props?.auth.permissions;

const hasManageRequestsPermissions = permissions?.includes(PermissionEnum.MANAGE_TIME_OFF_REQUESTS);

const breadcrumbs = [{ title: 'Staff', href: '/staff' }];

defineProps<PageProps>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-y-4 p-6">
            <AppTable
                v-if="hasManageRequestsPermissions"
                :columns="pendingTimeOffColumns"
                :data="pendingRequests || []"
                table-title="Pending Time Off Requests"
            />
            <AppTable :columns="currentUserTimeOffColumns" :data="userTimeOff" table-title="Your Time Off" />
        </div>
    </AppLayout>
</template>

<style scoped></style>
