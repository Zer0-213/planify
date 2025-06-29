<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';
import Greeting from '@/pages/dashboard/partials/Greeting.vue';
import QuickActions from '@/pages/dashboard/partials/QuickActions.vue';
import TodaysShift from '@/pages/dashboard/partials/TodaysShift.vue';
import UpcomingShifts from '@/pages/dashboard/partials/UpcomingShifts.vue';
import WeeklyOverview from '@/pages/dashboard/partials/WeeklyOverview.vue';
import { PageProp } from '@/pages/dashboard/types/pageProps';
import { ShiftTime } from '@/pages/shifts/types/shiftTypes';
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
const props = defineProps<{
    todayShift: ShiftTime;
}>();

console.log(props.todayShift);

const user = computed(() => page.props.auth.user as User).value;
const upcomingShifts = [
    {
        id: 1,
        date: '2024-03-15',
        start_time: '09:00',
        end_time: '17:00',
        role: 'Cashier',
    },
    {
        id: 2,
        date: '2024-03-16',
        start_time: '10:00',
        end_time: '18:00',
        role: 'Burgers',
    },
    {
        id: 3,
        date: '2024-03-18',
        start_time: '08:00',
        end_time: '16:00',
        role: 'Fries',
    },
];

const weeklyOverview = computed(
    () =>
        page.props.weeklyOverview || [
            { day: 'Mon', shift: '9-5' },
            { day: 'Tue', shift: 'Off' },
            { day: 'Wed', shift: '9-1' },
            { day: 'Thu', shift: 'Off' },
            { day: 'Fri', shift: 'Off' },
            { day: 'Sat', shift: '12-8' },
            { day: 'Sun', shift: 'Off' },
        ],
).value;
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
                <TodaysShift :shift="todayShift" />
            </section>
            <section class="flex justify-center">
                <UpcomingShifts :upcomingShifts="upcomingShifts" />
            </section>
            <section class="rounded-xl bg-white p-6 shadow">
                <WeeklyOverview :weeklyOverview="weeklyOverview" />
            </section>
        </div>
    </AppLayout>
</template>
