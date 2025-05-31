<script lang="ts" setup>
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';
import { defineEmits, defineProps } from 'vue';

type StaffFormProps = {
    name: string;
    email: string;
    phoneNumber: string;
};

defineProps<{
    form: ReturnType<typeof useForm<StaffFormProps>>;
}>();

const emit = defineEmits<{
    (e: 'updateForm', value: StaffFormProps): void;
}>();
</script>

<template>
    <div class="mb-2 flex flex-col gap-2">
        <Label for="name" required>Staff Name</Label>
        <Input
            :model-value="form.name"
            name="name"
            placeholder="Enter Staff Name"
            type="text"
            @update:model-value="(value) => emit('updateForm', { ...form, name: value.toString() })"
        />
        <p v-if="form.errors.name" class="font-bold text-red-600">{{ form.errors.name }}</p>
    </div>

    <Label for="email" required>Email</Label>
    <Input
        :model-value="form.email"
        name="email"
        placeholder="Enter Email"
        type="email"
        @update:model-value="(value) => emit('updateForm', { ...form, email: value.toString() })"
    />
    <p v-if="form.errors.email" class="font-bold text-red-600">{{ form.errors.email }}</p>

    <div class="mb-2 flex flex-col gap-2">
        <Label for="phoneNumber">Phone Number</Label>
        <Input
            :model-value="form.phoneNumber"
            name="phoneNumber"
            placeholder="Enter Phone Number"
            type="tel"
            @update:model-value="(value) => emit('updateForm', { ...form, phoneNumber: value.toString() })"
        />
        <p v-if="form.errors.phoneNumber" class="font-bold text-red-600">{{ form.errors.phoneNumber }}</p>
    </div>
</template>
