<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderRefundNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private $booking;

    /**
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
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Refunded Successfully')
            ->greeting('Hello ' . $this->booking->billing_name . ',')
            ->line('We informing you that your order with Booking ID : ' . $this->booking->booking_id . ' has been Refunded Successfully.')
            ->line('Total Order Quantity: ' . $this->booking->total_order_quantity . ' items')
            ->line('Total Amount Paid:  Rs ' . $this->booking->total_amount_paid)
            ->line('Total Refunded Amount:  Rs ' . $this->booking->total_amount_paid)
            ->action('View Order Details', url('/myorder_detail/' . $this->booking->booking_id))
            ->line('Refund Initiated Successfully; It may take 3-7 Days to Credit the Original Payment Method')
            ->salutation('Regards, The Peak Pulse Market Team');;
    }
    public function toArray($notifiable)
    {
        return [
            'title' => 'Refunded Successfully',
            'message' => 'Your Order with Booking ID ' . $this->booking->booking_id . ' has been Refunded Successfully',
        ];
    }
}
