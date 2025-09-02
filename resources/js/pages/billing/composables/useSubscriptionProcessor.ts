import { computed, onMounted, onUnmounted, readonly, ref, type Ref } from 'vue';
import { router } from '@inertiajs/vue3';

type UseSubscriptionProcessorOptions = {
    checkInterval: number;
    maxWaitTime: number;
    onSuccess?: () => void;
    onTimeout?: () => void;
    onError?: (error: Error) => void;
};

export function useSubscriptionProcessor(options: UseSubscriptionProcessorOptions) {
    const elapsedTime = ref(0);
    const timedOut = ref(false);
    const isProcessing = ref(false);
    const interval: Ref<number> = ref(0);
    const elapsedInterval: Ref<number> = ref(0);

    const progressWidth = computed(() => {
        return Math.min((elapsedTime.value / options.maxWaitTime) * 100, 100);
    });

    const progressSeconds = computed(() => {
        return Math.round(elapsedTime.value / 1000);
    });

    const checkSubscriptionStatus = async (): Promise<boolean> => {
        try {
            const response = await fetch(route('billing.checkSubscription'), {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json();

            if (data.subscribed) {
                cleanup();
                options.onSuccess?.();
                router.visit(route('dashboard'), {
                    replace: true,
                    onSuccess: () => {},
                });
                return true;
            }
        } catch (error) {
            console.error('Error checking subscription:', error);
            options.onError?.(error as Error);
        }

        return false;
    };

    const startChecking = () => {
        isProcessing.value = true;
        timedOut.value = false;

        checkSubscriptionStatus();

        interval.value = setInterval(async () => {
            if (await checkSubscriptionStatus()) {
                return;
            }

            if (elapsedTime.value >= options.maxWaitTime) {
                timedOut.value = true;
                isProcessing.value = false;
                options.onTimeout?.();
                cleanup();
            }
        }, options.checkInterval);

        elapsedInterval.value = setInterval(() => {
            elapsedTime.value += 100;
        }, 100);
    };

    const cleanup = () => {
        isProcessing.value = false;

        if (interval.value !== 0) {
            clearInterval(interval.value);
            interval.value = 0;
        }
        if (elapsedInterval.value !== 0) {
            clearInterval(elapsedInterval.value);
            elapsedInterval.value = 0;
        }
    };

    const continueToDashboard = () => {
        cleanup();
        router.visit(route('dashboard'));
    };

    const checkAgain = () => {
        timedOut.value = false;
        elapsedTime.value = 0;
        startChecking();
    };

    const reset = () => {
        cleanup();
        elapsedTime.value = 0;
        timedOut.value = false;
        isProcessing.value = false;
    };

    onMounted(() => {
        startChecking();
    });

    onUnmounted(() => {
        cleanup();
    });

    return {
        elapsedTime: readonly(elapsedTime),
        timedOut: readonly(timedOut),
        isProcessing: readonly(isProcessing),

        progressWidth,
        progressSeconds,

        startChecking,
        continueToDashboard,
        checkAgain,
        reset,
        cleanup,
    };
}
