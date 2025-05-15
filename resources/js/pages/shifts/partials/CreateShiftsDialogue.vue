<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { WeekPicker } from '@/components/ui/week-picker';
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="outline">Create a shift</Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Create a shift</DialogTitle>
            </DialogHeader>
            <WeekPicker />
        </DialogContent>
    </Dialog>
</template>

<style scoped></style>
