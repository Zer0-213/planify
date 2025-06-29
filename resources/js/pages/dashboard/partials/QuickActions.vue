<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Button class="rounded-xl bg-blue-600 px-5 py-2 text-white hover:bg-blue-700">Request Time Off</Button>
    <Link class="rounded-xl bg-gray-700 px-5 py-2 text-white hover:bg-gray-800" href="/shifts">View Full Rota</Link>
    <Button class="rounded-xl bg-green-600 px-5 py-2 text-white hover:bg-green-700">My Availability</Button>
</template>

<style scoped></style>
