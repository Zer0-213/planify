<script lang="ts" setup>
import { FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { defineProps } from 'vue';

defineProps<{
    onSubmit: (event: Event) => void;
}>();
</script>

<template>
    <form id="dialogForm" class="space-y-4" @submit="onSubmit">
        <FormField v-slot="{ componentField }" name="name">
            <FormItem>
                <FormLabel>Name</FormLabel>
                <FormDescription> Staff member's full name.</FormDescription>
                <FormControl>
                    <Input placeholder="Enter Name" type="text" v-bind="componentField" />
                </FormControl>
                <FormMessage />
            </FormItem>
        </FormField>
        <FormField v-slot="{ componentField }" name="email">
            <FormItem>
                <FormLabel>Email</FormLabel>
                <FormDescription> Staff member's email address.</FormDescription>
                <FormControl>
                    <Input placeholder="Enter Email" type="email" v-bind="componentField" />
                </FormControl>
                <FormMessage />
            </FormItem>
        </FormField>
        <FormField v-slot="{ componentField }" name="phoneNumber">
            <FormItem>
                <FormLabel>Phone Number</FormLabel>
                <FormDescription> Staff member's phone number.</FormDescription>
                <FormControl>
                    <Input placeholder="Enter Phone" type="tel" v-bind="componentField" />
                </FormControl>
                <FormMessage />
            </FormItem>
        </FormField>
        <FormField v-slot="{ componentField }" name="wage">
            <FormItem>
                <FormLabel>Wage</FormLabel>
                <FormDescription> Staff member's wage</FormDescription>
                <FormControl>
                    <Input placeholder="Enter Wage" type="number" v-bind="componentField" />
                </FormControl>
                <FormMessage />
            </FormItem>
        </FormField>
        <FormField v-slot="{ componentField }" name="permissions">
            <FormItem>
                <FormLabel>Permissions</FormLabel>
                <FormDescription> Select the permissions for staff member</FormDescription>
                <Select v-bind="componentField">
                    <FormControl>
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select Permissions" />
                        </SelectTrigger>
                    </FormControl>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem value="admin">Admin</SelectItem>
                            <SelectItem value="manager">Manager</SelectItem>
                            <SelectItem value="staff">Staff</SelectItem>
                            <SelectItem value="guest">Guest</SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
                <FormMessage />
            </FormItem>
        </FormField>
    </form>
</template>

<style scoped></style>
