<script lang="ts" setup>
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const form = ref({
    name: '',
    phone: '',
    type: '',
    customType: '',
    processing: false,
});

const companyTypes = [
    { value: 'healthCare', label: 'Health Care' },
    { value: 'retailHospitality', label: 'Retail/Hospitality' },
    { value: 'service', label: 'Services' },
    { value: 'charity', label: 'Charity' },
    { value: 'custom', label: 'Other' },
];

defineProps<{
    errors: {
        phone: string | null;
        name: string | null;
        type: string | null;
    };
}>();

const submit = () => {
    form.value.processing = true;
    const type = form.value.type === 'custom' ? form.value.customType : form.value.type;

    router.post(
        '/company/store',
        { ...form.value, type },
        {
            onFinish: () => (form.value.processing = false),
        },
    );
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center">
        <div class="mx-auto h-full w-full max-w-2xl px-4 py-8">
            <Card>
                <form class="space-y-6 p-6" @submit.prevent="submit">
                    <h2 class="text-center text-2xl font-bold">Create Your Company</h2>

                    <div class="space-y-2">
                        <Label :required="true" for="company-name">Company Name</Label>
                        <Input id="company-name" v-model="form.name" placeholder="Enter company name" required type="text" />
                        <InputError v-if="errors.name" :message="errors.name" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <Label for="company-phone">Phone Number</Label>
                        <Input id="company-phone" v-model="form.phone" placeholder="Enter company phone number" />
                        <InputError v-if="errors.phone" :message="errors.phone" class="mt-2" />
                    </div>

                    <div class="flex w-full flex-col space-y-2">
                        <Label :required="true" for="company-type">Company Type</Label>
                        <Select v-model="form.type" class="w-full">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select a Company Type" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="type in companyTypes" :key="type.value" :value="type.value">
                                        {{ type.label }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <Input v-if="form.type === 'custom'" v-model="form.customType" placeholder="Enter company type" required type="text" />
                        <InputError v-if="errors.type" :message="errors.type" class="mt-2" />
                    </div>

                    <Button :loading="form.processing" class="mx-auto block w-1/2" type="submit">Create Company</Button>
                </form>
            </Card>
        </div>
    </div>
</template>
