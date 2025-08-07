import { PendingTimeOffRequests } from '@/pages/timeOff/types/pendingTimeOffRequests';
import { UserTimeOffRequest } from '@/pages/timeOff/types/userTimeOffRequest';
import { Paginated } from '@/types/paginated';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

export type SearchableTimeOffItem = {
    company_user?: { user?: { name?: string } };
    reason?: string | null;
    status?: string;
    [key: string]: any;
};

type UseTimeOffSearchProps = {
    initialQuery?: string;
    currentFilters?: { q?: string };
    upcomingTimeOff: Paginated<UserTimeOffRequest>;
    pendingRequests: Array<PendingTimeOffRequests> | null;
};

export function useTimeOffSearch({ initialQuery = '', currentFilters, upcomingTimeOff, pendingRequests }: UseTimeOffSearchProps) {
    // Search state
    const q = ref<string>(initialQuery);
    const submittedQuery = computed(() => (currentFilters?.q ?? '').trim());
    const activeQuery = computed(() => q.value.trim());
    const isClientFiltering = computed(() => activeQuery.value !== submittedQuery.value);

    // Filtering utility
    const normalizeSearchTerm = (value: unknown): string => (value ?? '').toString().toLowerCase();

    const filterItems = <T extends SearchableTimeOffItem>(items: T[], searchTerm: string): T[] => {
        if (!searchTerm) return items;

        const normalizedTerm = normalizeSearchTerm(searchTerm);

        return items.filter((item) => {
            const searchableFields = [item.company_user?.user?.name, item.reason, item.status].map(normalizeSearchTerm);

            return searchableFields.some((field) => field.includes(normalizedTerm));
        });
    };

    // Filtered data - use client filtering when typing, server data otherwise
    const displayedPendingRequests = computed(() => {
        const baseData = pendingRequests || [];
        return isClientFiltering.value ? filterItems(baseData, activeQuery.value) : baseData;
    });

    const displayedUpcomingTimeOff = computed(() => {
        const baseData = upcomingTimeOff?.data || [];
        return isClientFiltering.value ? filterItems(baseData, activeQuery.value) : baseData;
    });

    // Search submission
    const submitSearch = (event?: Event) => {
        event?.preventDefault();

        const queryParam = activeQuery.value || undefined;
        router.get(
            window.location.pathname,
            { q: queryParam },
            {
                preserveState: true,
                replace: true,
            },
        );
    };

    return {
        q,
        submittedQuery,
        activeQuery,
        isClientFiltering,
        displayedPendingRequests,
        displayedUpcomingTimeOff,
        filterItems,
        submitSearch,
    };
}
