<script lang="ts" setup>
import AppTable from '@/components/AppTable/AppTable.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogTitle } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { h, ref } from 'vue';
import { toast } from 'vue-sonner';

// Define props for the component
defineProps<{
    companies: {
        id: number;
        name: string;
        phone_number: string;
        type: string;
        owner_id: number;
        created_at: string;
        updated_at: string;
    }[];
}>();

const breadcrumbs = [
    { title: 'Settings', href: '/settings' },
    { title: 'Companies', href: '/settings/companies' },
];

const permissions = usePage<SharedData>().props?.auth?.permissions as string[];

const canManageCompanies = permissions.includes('manage_company');

const isDialogOpen = ref(false);
const selectedCompany = ref<{ id: number; name: string; action: 'disable' | 'delete' } | null>(null);

const openConfirmDialog = (companyId: number, companyName: string, action: 'disable' | 'delete') => {
    selectedCompany.value = {
        id: companyId,
        name: companyName,
        action,
    };
    isDialogOpen.value = true;
};

const handleConfirmedAction = () => {
    if (!selectedCompany.value) return;

    const { id, action } = selectedCompany.value;

    if (action === 'disable') {
        router.patch(
            `/companies/${id}/disable`,
            {},
            {
                onSuccess: () => {
                    toast.success('Company disabled successfully');
                    isDialogOpen.value = false;
                },
                onError: () => {
                    toast.error('Failed to disable company');
                },
            },
        );
    } else if (action === 'delete') {
        router.delete(`/companies/${id}`, {
            onSuccess: () => {
                toast.success('Company deleted successfully');
                isDialogOpen.value = false;
            },
            onError: () => {
                toast.error('Failed to delete company');
            },
        });
    }
};

const columns = [
    {
        accessorKey: 'name',
        header: 'Company Name',
    },
    {
        accessorKey: 'type',
        header: 'Type',
    },
    {
        accessorKey: 'phone_number',
        header: 'Phone Number',
        cell: ({ row }) => {
            const phone = row.original.phone_number;
            return phone || 'N/A';
        },
    },
    {
        id: 'actions',
        header: 'Actions',
        cell: ({ row }) => {
            if (!canManageCompanies) return null;

            return h('div', { class: 'flex space-x-2' }, [
                h(
                    Button,
                    {
                        variant: 'outline',
                        size: 'sm',
                        onClick: () => openConfirmDialog(row.original.id, row.original.name, 'disable'),
                    },
                    () => 'Disable',
                ),
                h(
                    Button,
                    {
                        variant: 'destructive',
                        size: 'sm',
                        onClick: () => openConfirmDialog(row.original.id, row.original.name, 'delete'),
                    },
                    () => 'Delete',
                ),
            ]);
        },
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <section class="mt-6 w-full">
                <AppTable :columns="columns" :data="companies" table-title="Company Information" />
            </section>
        </div>

        <!-- Confirmation Dialog -->
        <Dialog v-model:open="isDialogOpen">
            <DialogContent>
                <DialogTitle>Confirm {{ selectedCompany?.action === 'delete' ? 'Deletion' : 'Disabling' }}</DialogTitle>
                <DialogDescription>
                    Are you sure you want to {{ selectedCompany?.action === 'delete' ? 'delete' : 'disable' }}
                    <strong>{{ selectedCompany?.name }}</strong
                    >?
                    <div class="mt-2">
                        <p v-if="selectedCompany?.action === 'delete'" class="text-red-500">
                            This action cannot be undone. All data associated with this company will be permanently removed.
                        </p>
                        <p v-else class="text-amber-500">This will prevent users from accessing this company until it is re-enabled.</p>
                    </div>
                </DialogDescription>

                <DialogFooter>
                    <Button variant="outline" @click="isDialogOpen = false">Cancel</Button>
                    <Button :variant="selectedCompany?.action === 'delete' ? 'destructive' : 'default'" @click="handleConfirmedAction">
                        {{ selectedCompany?.action === 'delete' ? 'Delete' : 'Disable' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

<style scoped></style>
