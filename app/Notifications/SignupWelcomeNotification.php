<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TemporaryUser;  // Import the TemporaryUser class

class SignupWelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    //protected $otp;
    private $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp; // Pass the OTP when creating the notification
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    /*{
        return (new MailMessage)
            ->subject('Welcome to Peak Pulse Market')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for signing up with us.')
            ->line('Your OTP for email verification is: **' . $this->otp . '**') // Include the OTP
            ->action('Shop With Us', route('index'))
            ->line('We are glad to have you on board!')
            ->salutation('') // Remove the "Regards, Laravel" default text received in mail
            ->salutation('Warm Regards, The Peak Pulse Team');
    }*/
    {
        if ($notifiable instanceof TemporaryUser) {
            return (new MailMessage)
                ->subject('Welcome to Peak Pulse Market')
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line('Thank you for signing up with us.')
                ->line('Your OTP for email verification is: **' . $this->otp . '**')
                ->action('Shop With Us', route('index'))
                ->line('We are glad to have you on board!')
                ->salutation('Warm Regards, The Peak Pulse Market Team');
        } else {
            throw new \Exception('Invalid notifiable object');
        }
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Welcome email sent successfully.',
        ];
    }
}
