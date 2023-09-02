<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PendingWithdrawal extends Notification implements ShouldQueue
{
    use Queueable;

    public $withdrawal;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Set subject
        $subject = "[" . config('app.name') . "] " . __('messages.t_subject_admin_pending_withdrawal');

        return (new MailMessage)
                    ->subject($subject)
                    ->greeting(__('messages.t_hi_admin'))
                    ->line(__('messages.t_notification_admin_pending_withdrawal'))
                    ->action(__('messages.t_pending_withdrawals'), admin_url('withdrawals'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
