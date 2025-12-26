<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClassReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $classSchedule;

    public function __construct($classSchedule)
    {
        $this->classSchedule = $classSchedule;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Class Reminder: '.$this->classSchedule->gymClass->title)
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Reminder: You have a class scheduled tomorrow.')
            ->line('Class: '.$this->classSchedule->gymClass->title)
            ->line('Time: '.$this->classSchedule->start_time->format('h:i A').' - '.$this->classSchedule->end_time->format('h:i A'))
            ->line('Date: '.$this->classSchedule->date->format('l, M d, Y'))
            ->action('View Schedule', route('dashboard'))
            ->line('See you in class!');
    }

    public function toArray($notifiable)
    {
        return [
            'class_id' => $this->classSchedule->id,
            'class_name' => $this->classSchedule->gymClass->title,
            'time' => $this->classSchedule->start_time->format('h:i A'),
            'message' => 'Reminder: '.$this->classSchedule->gymClass->title.' tomorrow at '.$this->classSchedule->start_time->format('h:i A'),
        ];
    }
}
