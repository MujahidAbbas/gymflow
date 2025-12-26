<?php

namespace App\Services\Payment;

use App\Models\PaymentTransaction;
use App\Models\Subscription;
use Exception;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;

class StripePaymentGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe Checkout Session
     */
    public function createPaymentSession(Subscription $subscription, array $options = []): array
    {
        try {
            $plan = $subscription->plan;
            $member = $subscription->member;

            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $plan->name,
                            'description' => $plan->description,
                        ],
                        'unit_amount' => $plan->price * 100, // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('subscriptions.success', ['subscription' => $subscription->id]),
                'cancel_url' => route('subscriptions.cancel', ['subscription' => $subscription->id]),
                'client_reference_id' => $subscription->id,
                'customer_email' => $member->email,
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'member_id' => $member->id,
                ],
            ]);

            return [
                'session_id' => $session->id,
                'url' => $session->url,
            ];
        } catch (Exception $e) {
            throw new Exception('Stripe payment session creation failed: '.$e->getMessage());
        }
    }

    /**
     * Handle Stripe webhook
     */
    public function handleWebhook(array $payload): PaymentTransaction
    {
        $event = Webhook::constructEvent(
            json_encode($payload),
            request()->header('Stripe-Signature'),
            config('services.stripe.webhook_secret')
        );

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $subscription = Subscription::find($session->metadata->subscription_id);

            $transaction = PaymentTransaction::create([
                'subscription_id' => $subscription->id,
                'member_id' => $subscription->member_id,
                'gateway_transaction_id' => $session->payment_intent,
                'payment_gateway' => 'stripe',
                'amount' => $session->amount_total / 100,
                'currency' => strtoupper($session->currency),
                'status' => 'completed',
                'type' => 'subscription',
                'description' => 'Subscription payment for '.$subscription->plan->name,
                'metadata' => $payload,
                'paid_at' => now(),
            ]);

            // Activate subscription
            $subscription->update([
                'status' => 'active',
                'payment_gateway' => 'stripe',
                'gateway_subscription_id' => $session->id,
            ]);

            return $transaction;
        }

        throw new Exception('Unhandled webhook event type');
    }

    /**
     * Cancel Stripe subscription
     */
    public function cancelSubscription(string $gatewaySubscriptionId): bool
    {
        try {
            \Stripe\Subscription::update($gatewaySubscriptionId, [
                'cancel_at_period_end' => true,
            ]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $gatewayTransactionId): string
    {
        try {
            $paymentIntent = \Stripe\PaymentIntent::retrieve($gatewayTransactionId);

            return $paymentIntent->status;
        } catch (Exception $e) {
            return 'unknown';
        }
    }

    /**
     * Refund payment
     */
    public function refundPayment(string $gatewayTransactionId, float $amount): bool
    {
        try {
            \Stripe\Refund::create([
                'payment_intent' => $gatewayTransactionId,
                'amount' => $amount * 100,
            ]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
