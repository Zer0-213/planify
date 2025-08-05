import { Paginated } from '@/types/paginated';
import { router } from '@inertiajs/vue3';

export const usePageNavigation = <T>(isPaginated: boolean, paginationInfo: Paginated<T> | null) => {
    const goToPage = (page: number) => {
        if (!isPaginated || !paginationInfo || page < 1 || page > paginationInfo.last_page) return;

        // Get current URL and update page parameter
        const url = new URL(window.location.href);
        url.searchParams.set('page', page.toString());

        router.get(
            url.pathname + url.search,
            {},
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    return { goToPage };
};
