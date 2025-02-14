<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderDeliveredNotification extends Notification implements ShouldQueue
{
    use Queueable;
    //public $queue = 'delivery';  //gave que name from here then not given from controller and model inside

    private $booking;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\tblbooking $booking
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Has Been Delivered')
            ->greeting('Hello ' . $this->booking->billing_name . ',')
            ->line('We are pleased to inform you that your order with Booking ID : ' . $this->booking->booking_id . ' has been delivered.')
            //->line('Order Details:')
            //->line('Order ID: ' . $this->booking->booking_id)
            ->line('Total Order Quantity: ' . $this->booking->total_order_quantity . ' items')
            ->line('Total Amount Paid:  Rs ' . $this->booking->total_amount_paid)
            ->line('Billing Address: ' . $this->booking->billing_address)
            ->action('View Order Details', url('/myorder_detail/' . $this->booking->booking_id))
            ->line('Thank you for shopping with us!')
            ->salutation('Regards, The Peak Pulse Market Team');;
    }
    public function toArray($notifiable)
    {
        return [
            'title' => 'Order Delivered',
            'message' => 'Your Order with Booking ID ' . $this->booking->booking_id . ' has been Delivered.',
        ];
    }
}
