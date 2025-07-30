<script lang="ts" setup>
import AppTable from '@/components/AppTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { columns } from '@/pages/timeOff/partials/table/columns';
import { CompanyTimeOffRequest } from '@/pages/timeOff/types/CompanyTimeOffRequest';
import { UserTimeOffRequest } from '@/pages/timeOff/types/userTimeOffRequest';
import { defineProps } from 'vue';

type PageProps = {
    userTimeOff: Array<UserTimeOffRequest>;
    companyTimeOffRequests: Array<CompanyTimeOffRequest> | null;
};

const breadcrumbs = [{ title: 'Staff', href: '/staff' }];

defineProps<PageProps>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <AppTable :columns="columns" :data="userTimeOff" table-title="Your Time Off" />
        </div>
    </AppLayout>
</template>

<style scoped></style>
