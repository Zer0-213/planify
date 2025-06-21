<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: '/settings/appearance',
    },
];

const env = import.meta.env;
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Appearance settings" />

        <SettingsLayout>
            <div v-if="!env.DEV" class="space-y-6">
                <HeadingSmall description="Update your account's appearance settings" title="Appearance settings" />
                <AppearanceTabs />
            </div>
            <div v-else class="flex h-96 items-center justify-center">
                <HeadingSmall title="Coming Soon" />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
