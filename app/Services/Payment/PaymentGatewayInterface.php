<?php

namespace App\Services\Payment;

use App\Models\PaymentTransaction;
use App\Models\Subscription;

interface PaymentGatewayInterface
{
    /**
     * Create a payment session
     */
    public function createPaymentSession(Subscription $subscription, array $options = []): array;

    /**
     * Process payment webhook
     */
    public function handleWebhook(array $payload): PaymentTransaction;

    /**
     * Cancel subscription
     */
    public function cancelSubscription(string $gatewaySubscriptionId): bool;

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $gatewayTransactionId): string;

    /**
     * Refund payment
     */
    public function refundPayment(string $gatewayTransactionId, float $amount): bool;
}
