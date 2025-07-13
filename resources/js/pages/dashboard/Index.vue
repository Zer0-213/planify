<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';
import CurrentWeekShifts from '@/pages/dashboard/partials/CurrentWeekShifts.vue';
import Greeting from '@/pages/dashboard/partials/Greeting.vue';
import QuickActions from '@/pages/dashboard/partials/QuickActions.vue';
import TodaysShift from '@/pages/dashboard/partials/TodaysShift.vue';
import { PageProp } from '@/pages/dashboard/types/pageProps';
import { UserShift } from '@/pages/shifts/types/shiftTypes';
import { type BreadcrumbItem, User } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, defineProps } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage<PageProp>();
defineProps<{
    todayShift: UserShift;
    upcomingShifts: UserShift;
}>();

const user = computed(() => page.props.auth.user as User).value;
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-10 p-6">
            <section>
                <Greeting :user="user" />
            </section>

            <section class="flex flex-wrap justify-center gap-4">
                <QuickActions />
            </section>
            <section class="flex w-full flex-col items-center justify-center">
                <TodaysShift :shift="todayShift.shifts" />
            </section>
            <section class="rounded-xl bg-white p-6 shadow">
                <CurrentWeekShifts :weeklyOverview="upcomingShifts.shifts" />
            </section>
        </div>
    </AppLayout>
</template>
