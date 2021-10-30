<?php

namespace App\Mail;

use App\Models\Notification;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderNotificationEmail extends Mailable
{
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param Notification $notification
     * @return void
     */
    public function __construct(public Notification $notification) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = '#'; //TODO order url will be generated after front-end done with order visible endpoint
        return $this->markdown('mails.order_notification', compact('url'));
    }
}
