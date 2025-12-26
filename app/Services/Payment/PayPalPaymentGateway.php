<?php

namespace App\Services\Payment;

use App\Models\PaymentTransaction;
use App\Models\Subscription;
use Exception;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalPaymentGateway implements PaymentGatewayInterface
{
    protected $apiContext;

    public function __construct()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            )
        );

        $this->apiContext->setConfig([
            'mode' => config('services.paypal.mode'), // sandbox or live
        ]);
    }

    /**
     * Create PayPal payment
     */
    public function createPaymentSession(Subscription $subscription, array $options = []): array
    {
        try {
            $plan = $subscription->plan;
            $member = $subscription->member;

            $payer = new Payer;
            $payer->setPaymentMethod('paypal');

            $item = new Item;
            $item->setName($plan->name)
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($plan->price);

            $itemList = new ItemList;
            $itemList->setItems([$item]);

            $amount = new Amount;
            $amount->setCurrency('USD')
                ->setTotal($plan->price);

            $transaction = new Transaction;
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription('Subscription: '.$plan->name)
                ->setInvoiceNumber('SUB-'.$subscription->id);

            $redirectUrls = new RedirectUrls;
            $redirectUrls->setReturnUrl(route('subscriptions.paypal.success', ['subscription' => $subscription->id]))
                ->setCancelUrl(route('subscriptions.cancel', ['subscription' => $subscription->id]));

            $payment = new Payment;
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

            $payment->create($this->apiContext);

            return [
                'payment_id' => $payment->getId(),
                'url' => $payment->getApprovalLink(),
            ];
        } catch (Exception $e) {
            throw new Exception('PayPal payment creation failed: '.$e->getMessage());
        }
    }

    /**
     * Handle PayPal webhook/callback
     */
    public function handleWebhook(array $payload): PaymentTransaction
    {
        try {
            $paymentId = $payload['paymentId'];
            $payerId = $payload['PayerID'];

            $payment = Payment::get($paymentId, $this->apiContext);

            $execution = new PaymentExecution;
            $execution->setPayerId($payerId);

            $result = $payment->execute($execution, $this->apiContext);

            $subscriptionId = str_replace('SUB-', '', $result->getTransactions()[0]->getInvoiceNumber());
            $subscription = Subscription::find($subscriptionId);

            $transaction = PaymentTransaction::create([
                'subscription_id' => $subscription->id,
                'member_id' => $subscription->member_id,
                'gateway_transaction_id' => $paymentId,
                'payment_gateway' => 'paypal',
                'amount' => $result->getTransactions()[0]->getAmount()->getTotal(),
                'currency' => 'USD',
                'status' => 'completed',
                'type' => 'subscription',
                'description' => 'Subscription payment for '.$subscription->plan->name,
                'metadata' => $payload,
                'paid_at' => now(),
            ]);

            // Activate subscription
            $subscription->update([
                'status' => 'active',
                'payment_gateway' => 'paypal',
                'gateway_subscription_id' => $paymentId,
            ]);

            return $transaction;
        } catch (Exception $e) {
            throw new Exception('PayPal webhook handling failed: '.$e->getMessage());
        }
    }

    /**
     * Cancel PayPal subscription (not directly supported for one-time payments)
     */
    public function cancelSubscription(string $gatewaySubscriptionId): bool
    {
        // PayPal recurring billing would require different implementation
        return true;
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $gatewayTransactionId): string
    {
        try {
            $payment = Payment::get($gatewayTransactionId, $this->apiContext);

            return $payment->getState();
        } catch (Exception $e) {
            return 'unknown';
        }
    }

    /**
     * Refund PayPal payment
     */
    public function refundPayment(string $gatewayTransactionId, float $amount): bool
    {
        // Would require implementing PayPal Sale refund
        return false;
    }
}
