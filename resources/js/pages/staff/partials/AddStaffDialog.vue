<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import StaffForm from '@/pages/staff/partials/staffForm/StaffForm.vue';
import { useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

type StaffFormData = {
    name: string;
    email: string;
    phoneNumber: string;
};

const form = useForm({
    name: '',
    email: '',
    phoneNumber: '',
});

const handleFormEmit = (newForm: StaffFormData) => {
    form.name = newForm.name;
    form.email = newForm.email;
    form.phoneNumber = newForm.phoneNumber;
};
</script>

<template>
    <section class="mb-4 flex w-full justify-end">
        <Dialog>
            <DialogTrigger as-child>
                <Button>Invite Staff Member</Button>
            </DialogTrigger>
            <DialogContent>
                <form
                    id="addStaffForm"
                    class="flex w-full flex-col gap-4"
                    @submit.prevent="
                        form.post('/staff/invite', {
                            onSuccess: () => {
                                toast.success('Staff member invited successfully');
                                form.reset();
                            },
                        })
                    "
                >
                    <DialogTitle>Invite New Staff Member</DialogTitle>
                    <DialogDescription>Invite a user to your company's team</DialogDescription>
                    <StaffForm :form="form" @update-form="handleFormEmit" />

                    <DialogFooter>
                        <div class="flex w-full flex-col gap-2">
                            <Button :disabled="form.processing" class="w-full" form="addStaffForm" type="submit"> Invite Staff </Button>
                        </div>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </section>
</template>

<style scoped></style>
