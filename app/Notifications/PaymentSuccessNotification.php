<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment Successful')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Your payment was successful!')
            ->line('Transaction ID: '.$this->transaction->transaction_id)
            ->line('Amount: '.$this->transaction->formatted_amount)
            ->line('Payment Gateway: '.ucfirst($this->transaction->payment_gateway))
            ->action('View Subscription', route('subscriptions.mine'))
            ->line('Thank you for your payment!');
    }

    public function toArray($notifiable)
    {
        return [
            'transaction_id' => $this->transaction->transaction_id,
            'amount' => $this->transaction->amount,
            'message' => 'Payment of '.$this->transaction->formatted_amount.' was successful',
        ];
    }
}
