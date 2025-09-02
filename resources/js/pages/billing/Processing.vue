<script lang="ts" setup>
import { useSubscriptionProcessor } from '@/pages/billing/composables/useSubscriptionProcessor';

const props = defineProps<{
    checkInterval: number;
    maxWaitTime: number;
}>();

const { timedOut, progressWidth, continueToDashboard, elapsedTime, checkAgain } = useSubscriptionProcessor({
    checkInterval: props.checkInterval,
    maxWaitTime: props.maxWaitTime,
    onSuccess: () => {
        console.log('Subscription activated successfully!');
    },
    onTimeout: () => {
        console.log('Subscription processing timed out');
    },
    onError: (error) => {
        console.error('Subscription processing error:', error);
    },
});
</script>

<template>
    <div class="flex min-h-screen flex-col justify-center bg-gray-50 py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white px-4 py-8 shadow sm:rounded-lg sm:px-10">
                <div class="text-center">
                    <!-- Loading Spinner -->
                    <div class="mb-6 flex justify-center">
                        <div v-if="!timedOut" class="h-16 w-16 animate-spin rounded-full border-b-2 border-indigo-600"></div>
                        <div v-else class="flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100">
                            <svg class="h-8 w-8 text-yellow-600" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                ></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Status Messages -->
                    <div v-if="!timedOut">
                        <h2 class="mb-2 text-2xl font-bold text-gray-900">Processing Your Subscription</h2>
                        <p class="mb-4 text-gray-600">Thank you for your payment! We're activating your account now.</p>
                        <p class="text-sm text-gray-500">This usually takes just a few seconds...</p>

                        <!-- Progress indicator -->
                        <div class="mt-6">
                            <div class="h-2 rounded-full bg-gray-200">
                                <div :style="{ width: progressWidth + '%' }" class="h-2 rounded-full bg-indigo-600 transition-all duration-500"></div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">{{ Math.round(elapsedTime / 1000) }}s elapsed</p>
                        </div>
                    </div>

                    <div v-else>
                        <h2 class="mb-2 text-2xl font-bold text-gray-900">Taking Longer Than Expected</h2>
                        <p class="mb-6 text-gray-600">
                            Don't worry! Your payment was successful, but subscription activation is taking a bit longer than usual.
                        </p>

                        <div class="space-y-3">
                            <button
                                class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                                @click="continueToDashboard"
                            >
                                Continue to Dashboard
                            </button>

                            <button
                                class="flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                                @click="checkAgain"
                            >
                                Check Again
                            </button>
                        </div>

                        <p class="mt-4 text-xs text-gray-500">
                            Your subscription will be activated automatically. You can also check your email for confirmation.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
