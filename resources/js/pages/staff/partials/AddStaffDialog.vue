<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import StaffForm from '@/pages/staff/partials/staffForm/StaffForm.vue';
import { StaffFormData } from '@/pages/staff/types/staffFormData';
import { Role } from '@/types/role';
import { useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { toast } from 'vue-sonner';

defineProps<{
    roles: Role[];
}>();

const form = useForm<StaffFormData>({
    name: '',
    email: '',
    phoneNumber: '',
    wage: 0,
    role: null,
});
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
                            onBefore: () => {
                                console.log(form);
                            },
                            onSuccess: () => {
                                toast.success('Staff member created successfully');
                                form.reset();
                            },
                        })
                    "
                >
                    <DialogTitle>Invite New Staff Member</DialogTitle>
                    <DialogDescription>Invite a user to your company's team</DialogDescription>
                    <StaffForm v-model:form="form" :roles="roles" />

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
