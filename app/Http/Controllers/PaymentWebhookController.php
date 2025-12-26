<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentWebhookController extends Controller
{
    /**
     * Handle Stripe webhook
     */
    public function stripe(Request $request): Response
    {
        try {
            $gateway = PaymentGatewayFactory::create('stripe');
            $transaction = $gateway->handleWebhook($request->all());

            return response('Webhook handled', 200);
        } catch (\Exception $e) {
            \Log::error('Stripe webhook failed: '.$e->getMessage());

            return response('Webhook failed', 400);
        }
    }

    /**
     * Handle PayPal webhook
     */
    public function paypal(Request $request): Response
    {
        try {
            $gateway = PaymentGatewayFactory::create('paypal');
            $transaction = $gateway->handleWebhook($request->all());

            return response('Webhook handled', 200);
        } catch (\Exception $e) {
            \Log::error('PayPal webhook failed: '.$e->getMessage());

            return response('Webhook failed', 400);
        }
    }
}
