<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import AddStaffForm from '@/pages/staff/partials/AddStaffForm.vue';
import { Form } from 'vee-validate';
</script>

<template>
    <span class="mb-4 flex w-full justify-end">
        <Form v-slot="{ handleSubmit }" as="">
            <Dialog>
                <DialogTrigger as-child>
                    <Button>Invite Staff Member</Button>
                </DialogTrigger>
                <DialogContent>
                    <DialogTitle>Invite New Staff Member</DialogTitle>
                    <DialogHeader>Add a new staff member to the team</DialogHeader>
                    <AddStaffForm :on-submit="handleSubmit" />
                    <DialogFooter>
                        <Button class="w-full" form="dialogForm" type="submit"> Invite Staff</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </Form>
    </span>
</template>

<style scoped></style>
