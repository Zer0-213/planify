<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Paddle\Http\Controllers\WebhookController;
use Symfony\Component\HttpFoundation\Response;

class PaddleWebhookController extends WebhookController
{
    /**
     * Handle a Paddle webhook call.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $payload = $request->all();

        Log::info('Paddle webhook received', [
            'event_type' => $payload['event_type'] ?? 'unknown',
            'payload' => $payload
        ]);

        $response = parent::__invoke($request);

        // Add any custom logic after the parent has processed the webhook
        $this->handleCustomLogic($payload);

        return $response;
    }

    /**
     * Handle custom business logic after webhook processing.
     *
     * @param array $payload
     * @return void
     */
    protected function handleCustomLogic(array $payload): void
    {
        $eventType = $payload['event_type'] ?? null;

        switch ($eventType) {
            case 'subscription.created':
                $this->onSubscriptionCreated($payload);
                break;

            case 'subscription.updated':
                $this->onSubscriptionUpdated($payload);
                break;

            case 'subscription.canceled':
                $this->onSubscriptionCanceled($payload);
                break;

            case 'transaction.completed':
                $this->onTransactionCompleted($payload);
                break;

            case 'transaction.payment_failed':
                $this->onPaymentFailed($payload);
                break;

            default:
                Log::info('No custom handling for webhook event', [
                    'event_type' => $eventType
                ]);
                break;
        }
    }

    /**
     * Custom logic when a subscription is created.
     *
     * @param array $payload
     * @return void
     */
    protected function onSubscriptionCreated(array $payload): void
    {
        $data = $payload['data'];

        Log::info('Custom subscription created handling', [
            'subscription_id' => $data['id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null
        ]);

        // Add your custom logic here,
        // For example,
        // - Send welcome email
        // - Update user roles/permissions
        // - Create internal records
        // - Trigger other business processes
    }

    /**
     * Custom logic when the subscription is updated.
     *
     * @param array $payload
     * @return void
     */
    protected function onSubscriptionUpdated(array $payload): void
    {
        $data = $payload['data'];

        Log::info('Custom subscription updated handling', [
            'subscription_id' => $data['id'] ?? null,
            'status' => $data['status'] ?? null
        ]);

        // Add your custom logic here,
        // For example,
        // - Update user permissions based on the new plan
        // - Send notification emails
        // - Update usage limits
    }

    /**
     * Custom logic when a subscription is canceled.
     *
     * @param array $payload
     * @return void
     */
    protected function onSubscriptionCanceled(array $payload): void
    {
        $data = $payload['data'];

        Log::info('Custom subscription canceled handling', [
            'subscription_id' => $data['id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null
        ]);

        // Add your custom logic here,
        // For example,
        // - Send cancellation confirmation
        // - Schedule data export
        // - Update user permissions
        // - Trigger retention campaigns
    }

    /**
     * Custom logic when the transaction is completed.
     *
     * @param array $payload
     * @return void
     */
    protected function onTransactionCompleted(array $payload): void
    {
        $data = $payload['data'];

        Log::info('Custom transaction completed handling', [
            'transaction_id' => $data['id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null,
            'amount' => $data['details']['totals']['grand_total'] ?? null
        ]);

        // Add your custom logic here,
        // For example,
        // - Send custom receipt
        // - Update internal billing records
        // - Trigger fulfillment processes
        // - Update analytics
    }

    /**
     * Custom logic when payment fails.
     *
     * @param array $payload
     * @return void
     */
    protected function onPaymentFailed(array $payload): void
    {
        $data = $payload['data'];

        Log::info('Custom payment failed handling', [
            'transaction_id' => $data['id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null
        ]);

        // Add your custom logic here,
        // For example,
        // - Send payment failure notifications
        // - Trigger-dunning management
        // - Update account status
        // - Send recovery emails
    }
}
