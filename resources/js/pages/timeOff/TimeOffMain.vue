<script lang="ts" setup>
import AppPagination from '@/components/AppPagination.vue';
import AppTable from '@/components/AppTable/AppTable.vue';
import { Input } from '@/components/ui/input';
import { PermissionEnum } from '@/enums/permissionEnum';
import AppLayout from '@/layouts/AppLayout.vue';
import { useTimeOffSearch } from '@/pages/timeOff/composables/useTimeOffSearch';
import { pendingTimeOffColumns } from '@/pages/timeOff/partials/table/pendingTimeOffColumns';
import { upComingTimeOffs } from '@/pages/timeOff/partials/table/upComingTimeOffs';
import { PendingTimeOffRequests } from '@/pages/timeOff/types/pendingTimeOffRequests';
import { UserTimeOffRequest } from '@/pages/timeOff/types/userTimeOffRequest';
import { SharedData } from '@/types';
import { Paginated } from '@/types/paginated';
import { usePage } from '@inertiajs/vue3';
import { computed, defineProps } from 'vue';

type PageProps = {
    upcomingTimeOff: Paginated<UserTimeOffRequest>;
    pendingRequests: Array<PendingTimeOffRequests> | null;
    filters?: { q?: string };
};

const props = defineProps<PageProps>();

const { auth } = usePage<SharedData>().props;
const hasManageRequestsPermissions = computed(() => auth.permissions?.includes(PermissionEnum.MANAGE_TIME_OFF_REQUESTS));
const breadcrumbs = [{ title: 'Staff', href: '/staff' }];

const { q, isClientFiltering, displayedPendingRequests, displayedUpcomingTimeOff, submitSearch } = useTimeOffSearch({
    initialQuery: props.filters?.q ?? '',
    currentFilters: props.filters,
    upcomingTimeOff: props.upcomingTimeOff,
    pendingRequests: props.pendingRequests,
});

const showPagination = computed(() => !isClientFiltering.value);
const hasPendingRequests = computed(() => hasManageRequestsPermissions.value && displayedPendingRequests.value.length > 0);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-y-6 p-6">
            <div class="flex flex-wrap justify-end gap-3">
                <Input
                    v-model="q"
                    class="w-full max-w-md rounded-md border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Search by name, reason, or status..."
                    type="text"
                    @keyup.enter="submitSearch"
                />
            </div>

            <AppTable
                v-if="hasPendingRequests"
                :columns="pendingTimeOffColumns"
                :data="displayedPendingRequests"
                table-title="Pending Time Off Requests"
            />

            <AppTable :columns="upComingTimeOffs" :data="displayedUpcomingTimeOff" table-title="Who's Off" />

            <div v-if="showPagination" class="flex flex-wrap items-center justify-center gap-2 py-4">
                <AppPagination :paginated-data="upcomingTimeOff" />
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
