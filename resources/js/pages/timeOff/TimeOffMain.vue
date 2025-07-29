<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { PageProps } from '@/pages/timeOff/types/pageProps';
import { defineProps } from 'vue';

const breadcrumbs = [{ title: 'Staff', href: '/staff' }];

const props = defineProps<PageProps>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">hello</AppLayout>
</template>

<style scoped></style>
