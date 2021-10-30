<?php

namespace App\Listeners;

use App\Events\OrderCreatedEvent;
use App\Mail\OrderNotificationEmail;
use App\Models\Notification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderNotification
{
    /**
     * Handle the event.
     *
     * @param  OrderCreatedEvent  $event
     * @return void
     */
    public function handle(OrderCreatedEvent $event)
    {
        $order = $event->order;
        $notification = $order->notification()->create([
            'title' => 'New order notification',
            'details' => 'A new order has been placed with the amount of '
                . $order->amount . 'Tk. under ' . config('app.name')
        ]);
        $this->notifyToAdmin($notification);
    }

    private function notifyToAdmin(Notification $notification)
    {
        try {
            $adminMail = config('mail.admin_mail');
            Mail::to($adminMail)->send(new OrderNotificationEmail($notification)); //TODO should be quable
            $notification->notified = true;
            $notification->save();
        } catch (Exception $e) {
            Log::error("Failed to send notification for order[" . $notification->notifiable->id . "]. Message: " . $e->getMessage());
        }
    }
}
