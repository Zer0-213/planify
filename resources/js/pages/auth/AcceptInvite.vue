<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps<{
    invite_id: string | null;
    invite_token: string | null;
    flash: {
        success?: string;
        error?: string;
    };
}>();

const form = useForm({
    password: '',
    password_confirmation: '',
    invite_id: props.invite_id,
    invite_token: props.invite_token,
});
</script>

<template>
    <AuthBase description="Create a password for your account" title="Create an account">
        <Head title="Register" />

        <div>
            <p class="text-muted-foreground text-sm">
                You have been invited to join the team.<br />Please create a password to complete your registration.
            </p>
            <p v-if="props.flash.success" class="text-sm text-green-600">{{ props.flash.success }}</p>
            <p v-if="props.flash.error" class="text-sm text-red-600">{{ props.flash.error }}</p>
        </div>

        <form class="space-y-6" @submit.prevent="form.post(`staff/create-from-invite`)">
            <div class="grid gap-2">
                <Label for="password">Password</Label>
                <Input id="password" v-model="form.password" autocomplete="new-password" name="password" required type="password" />
                <p v-if="form.errors.password" class="text-red-600">{{ form.errors.password }}</p>

                <Label for="password_confirmation">Confirm Password</Label>
                <Input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    autocomplete="new-password"
                    name="password_confirmation"
                    required
                    type="password"
                />
                <p v-if="form.errors.password_confirmation" class="text-red-600">
                    {{ form.errors.password_confirmation }}
                </p>
            </div>
            <Button :disabled="form.processing" class="mt-2 w-full" tabindex="5" type="submit"> Submit</Button>
        </form>
    </AuthBase>
</template>

<style scoped></style>
