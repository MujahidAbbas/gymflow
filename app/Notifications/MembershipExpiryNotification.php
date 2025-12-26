<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipExpiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;

    protected $daysRemaining;

    public function __construct($subscription, $daysRemaining)
    {
        $this->subscription = $subscription;
        $this->daysRemaining = $daysRemaining;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Membership is Expiring Soon')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Your membership will expire in '.$this->daysRemaining.' days.')
            ->line('Subscription: '.$this->subscription->plan->name)
            ->line('Expiry Date: '.$this->subscription->end_date->format('M d, Y'))
            ->action('Renew Now', route('subscriptions.index'))
            ->line('Thank you for being a valued member!');
    }

    public function toArray($notifiable)
    {
        return [
            'subscription_id' => $this->subscription->id,
            'days_remaining' => $this->daysRemaining,
            'message' => 'Your membership expires in '.$this->daysRemaining.' days',
        ];
    }
}
