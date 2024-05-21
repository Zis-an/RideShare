<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class LoginNeedsVerification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    /**
     * Send the notification as an SMS.
     *
     * @param  mixed  $notifiable
     * @return string|null
     */
    public function toTwilio($notifiable)
    {
        $loginCode = rand(111111, 999999);

        $notifiable->update([
            'login_code' => $loginCode
        ]);

        return (new TwilioSmsMessage())
            ->content("Your Chiki Monkey Login Code is {$loginCode}, Ei code karo sathe share korle guli koira maira falamu.");

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
