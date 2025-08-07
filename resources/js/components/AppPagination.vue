<script lang="ts" setup>
import { Pagination, PaginationContent, PaginationEllipsis, PaginationFirst, PaginationItem, PaginationLast } from '@/components/ui/pagination';
import { Paginated } from '@/types/paginated';
import { router } from '@inertiajs/vue3';
import { computed, defineProps } from 'vue';

type Props = {
    paginatedData: Paginated<any>;
};

const props = defineProps<Props>();

const handleClick = (link: { url: string | null }) => {
    if (link.url) router.visit(link.url);
};

const numberedLinks = computed(() =>
    props.paginatedData.links
        .filter((link) => !isNaN(Number(link.label)))
        .map((link) => ({
            ...link,
            label: Number(link.label),
        })),
);

const visibleLinks = computed(() => {
    const pagesToShow = 3;
    const current = props.paginatedData.current_page;
    const start = Math.max(current - 1, 1);
    const end = Math.min(start + pagesToShow - 1, props.paginatedData.last_page);

    return numberedLinks.value.filter((link) => link.label >= start && link.label <= end);
});

const showEllipsisAfter = computed(() => {
    const lastVisible = visibleLinks.value.at(-1);
    return lastVisible && lastVisible.label < props.paginatedData.last_page;
});
</script>

<template>
    <div class="flex flex-wrap items-center justify-center gap-2 py-4">
        <Pagination :items-per-page="paginatedData.per_page" :total="paginatedData.total">
            <PaginationContent>
                <PaginationFirst :disabled="paginatedData.current_page === 1" @click="handleClick({ url: paginatedData.first_page_url })" />

                <template v-for="(link, index) in visibleLinks" :key="index">
                    <PaginationItem :disabled="link.url === null" :is-active="link.active" :value="index" @click="handleClick(link)">
                        {{ link.label }}
                    </PaginationItem>
                </template>

                <PaginationEllipsis v-if="showEllipsisAfter" />

                <PaginationLast
                    :disabled="paginatedData.current_page === paginatedData.last_page"
                    @click="handleClick({ url: paginatedData.last_page_url })"
                />
            </PaginationContent>
        </Pagination>
    </div>
</template>
