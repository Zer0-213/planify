<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';

const breadcrumbs = [{ title: 'Staff', href: '/staff' }];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">hello</AppLayout>
</template>

<style scoped></style>
