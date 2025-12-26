<?php

namespace App\Services\Payment;

class PaymentGatewayFactory
{
    /**
     * Create payment gateway instance based on type
     */
    public static function create(string $gateway): PaymentGatewayInterface
    {
        return match ($gateway) {
            'stripe' => new StripePaymentGateway,
            'paypal' => new PayPalPaymentGateway,
            default => throw new \Exception("Unsupported payment gateway: {$gateway}"),
        };
    }

    /**
     * Get available gateways
     */
    public static function getAvailableGateways(): array
    {
        return [
            'stripe' => [
                'name' => 'Stripe',
                'description' => 'Credit/Debit Cards',
                'icon' => 'ri-bank-card-line',
            ],
            'paypal' => [
                'name' => 'PayPal',
                'description' => 'PayPal Account',
                'icon' => 'ri-paypal-line',
            ],
        ];
    }
}
