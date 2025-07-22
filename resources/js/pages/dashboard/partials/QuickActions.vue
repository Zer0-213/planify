<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import RequestTimeOffModal from '@/pages/dashboard/partials/RequestTimeOffModal.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <RequestTimeOffModal />
    <Link :href="route('shifts.index')" class="rounded-xl bg-gray-700 px-5 py-2 text-white hover:bg-gray-800">View Full Rota </Link>
    <Button class="rounded-xl bg-green-600 px-5 py-2 text-white hover:bg-green-700">My Availability</Button>
</template>
