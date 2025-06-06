<script lang="ts" setup>
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { StaffFormData } from '@/pages/staff/types/staffFormData';
import { useForm } from '@inertiajs/vue3';
import { defineEmits, defineProps, reactive, watch } from 'vue';

const props = defineProps<{
    form: ReturnType<typeof useForm<StaffFormData>>;
    roles: string[];
}>();

const emit = defineEmits<{
    (e: 'update:form', value: StaffFormData): void;
}>();

const localForm = reactive({ ...props.form });

// Watch for changes and emit
watch(
    localForm,
    (newVal) => {
        emit('update:form', { ...newVal });
    },
    { deep: true },
);

// Sync external prop changes to local form (optional, in case parent updates externally)
watch(
    () => props.form,
    (newVal) => {
        Object.assign(localForm, newVal);
    },
    { deep: true },
);
</script>

<template>
    <div class="mb-2 flex flex-col gap-2">
        <Label for="name" required>Staff Name</Label>
        <Input v-model="localForm.name" name="name" placeholder="Enter Staff Name" type="text" />
        <p v-if="form.errors.name" class="font-bold text-red-600">{{ form.errors.name }}</p>
    </div>

    <Label for="email" required>Email</Label>
    <Input v-model="localForm.email" autocomplete="off" name="email" placeholder="Enter Email" type="email" />
    <p v-if="form.errors.email" class="font-bold text-red-600">{{ form.errors.email }}</p>

    <div class="mb-2 flex flex-col gap-2">
        <Label for="phoneNumber">Phone Number</Label>
        <Input v-model="localForm.phoneNumber" name="phoneNumber" placeholder="Enter Phone Number" type="tel" />
        <p v-if="form.errors.phoneNumber" class="font-bold text-red-600">{{ form.errors.phoneNumber }}</p>
    </div>

    <div class="mb-2 flex flex-col gap-2">
        <Label for="wage">Wage</Label>
        <Input v-model="localForm.wage" name="wage" placeholder="Enter Wage" type="number" />
        <p v-if="form.errors.wage" class="font-bold text-red-600">{{ form.errors.wage }}</p>
    </div>

    <div class="mb-2 flex flex-col gap-2">
        <Label for="role" required>Role</Label>
        <Select v-model="localForm.role" class="w-full" name="role">
            <SelectTrigger class="w-full">
                <SelectValue placeholder="Select a Role" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel>Roles</SelectLabel>
                    <SelectItem v-for="role in roles" :key="role" :value="role">
                        {{ role }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
        <p v-if="form.errors.role" class="font-bold text-red-600">{{ form.errors.role }}</p>
    </div>
</template>
