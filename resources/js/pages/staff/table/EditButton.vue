<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Role } from '@/types/role';
import { useForm, usePage } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

const props = defineProps<{
    staffId: number;
    staffName: string;
    wage: number;
    currentRole: Role;
    roles: Role[];
}>();

const form = useForm({
    wage: props.wage,
    role_id: props.currentRole.id,
    roles: props.roles,
});

const user = usePage<{
    auth: {
        user: {
            id: number;
        };
        permissions: string[];
    };
}>().props?.auth?.user;
</script>

<template>
    <Dialog>
        <DialogTrigger>
            <Button as-child class="w-full text-blue-500 underline" variant="ghost">Edit</Button>
        </DialogTrigger>
        <DialogContent>
            <DialogTitle>Edit</DialogTitle>
            <DialogDescription>Edit {{ staffName }}</DialogDescription>
            <form :form="form">
                <div class="gap-1">
                    <Label for="wage">Wage</Label>
                    <Input id="wage" v-model="form.wage" :placeholder="`Current Wage: ${props.wage}`" class="mt-1" type="number" />
                </div>

                <div class="mt-4 space-y-1">
                    <Label for="role">Role</Label>
                    <Select v-model="form.role_id">
                        <SelectTrigger class="w-full">
                            <SelectValue :placeholder="props.currentRole.name || 'Select Role'" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Roles</SelectLabel>
                                <SelectItem v-for="role in props.roles" :key="role.id" :value="role.id"> {{ role.name }} </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>

                <DialogFooter class="justify-end, mt-4 flex flex-row gap-2">
                    <Button
                        v-if="staffId !== user.id"
                        :disabled="form.processing"
                        variant="destructive"
                        @click.prevent="
                            form.delete(route(`staff.destroy`, staffId), {
                                onSuccess: () => {
                                    toast.success('Staff member deleted successfully');
                                },
                            })
                        "
                    >
                        Delete
                    </Button>
                    <Button
                        :disabled="form.processing"
                        @click.prevent="
                            form.put(route('staff.update', staffId), {
                                onSuccess: () => {
                                    toast.success('Staff member updated successfully');
                                },
                                onError: (error) => {
                                    console.log(error);
                                    toast.error(error.error);
                                },
                                onFinish: () => {
                                    form.reset();
                                },
                            })
                        "
                    >
                        Submit
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

<style scoped></style>
