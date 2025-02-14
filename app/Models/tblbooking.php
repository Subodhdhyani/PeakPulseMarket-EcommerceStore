<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\OrderDeliveredNotification;
use App\Notifications\OrderRefundNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class tblbooking extends Model
{
    use HasFactory, Notifiable;
    protected $table = "tblbookings";
    protected $primaryKey = "id";
    protected $fillable = [
        'tbluser_id',
        'tblproduct_id',
        'order_id',
        'payment_id',
        'booking_id',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'payment_mode',
        'total_order_quantity',
        'sale_prices',
        'discount_prices',
        'total_amount_paid',
        'payment_status',
        'currency',
        'order_status',
    ];


    // Relationships
    public function user()
    {
        return $this->belongsTo(tbluser::class, 'tbluser_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(tblproduct::class, 'tblproduct_id', 'id');
    }

    // Define the relationship with tblorderhelp (one-to-many)
    public function tblorderhelps()
    {
        return $this->hasMany(tblorderhelp::class, 'booking_id', 'booking_id');
    }

    protected $casts = [
        'order_status' => 'integer',
    ];

    public function routeNotificationForMail()
    {
        /*this function is used to fetch email from tblbooking which is billing_email , normally this function not required when email
        name used in column  as it automatically fetch email from email column  and this function used for sending and storing build 
        in notification  when order delivered main function used below when order delivered */
        return $this->billing_email;
    }
    protected static function booted()
    {
        // Automatically create a tracking entry when a new booking is created
        static::created(function ($booking) {
            \App\Models\tbltracking::firstOrCreate([
                'booking_id' => $booking->booking_id,
            ]);
        });

        //Decrease stock/quantity from tblproduct when new booking inserted into tblbooking
        //In here only first tblproduct_id decrease for all decrease apply loop and also add multiple relationship b/w tblbooking and tblproduct
        static::updated(function ($booking) {
            // Check if the payment_status changed to 'Successful'  as default its pending
            if ($booking->isDirty('payment_status') && $booking->payment_status === 'Successful') {
                // Fetch the related product directly
                $product = tblproduct::find($booking->tblproduct_id);

                // Check if the product exists and has sufficient stock
                if ($product && $product->quantity > 0) {
                    // Decrement the product's quantity
                    $product->quantity -= 1;
                    $product->save();
                }
            }
        });


        // Automatically update timestamps in tbltracking when order_status changes in tblbooking
        static::updated(function ($booking) {
            // Check if the 'order_status' field was modified
            if ($booking->isDirty('order_status')) {
                $oldStatus = $booking->getOriginal('order_status'); // Old status
                $newStatus = $booking->order_status; // New status

                // List of valid transitions
                $validTransitions = [
                    '0' => ['1', '4'],
                    '1' => ['2', '4'],
                    '2' => ['3'],
                    '3' => ['4', '6'],
                    '4' => ['5'],
                    '6' => ['4'],    // this transition not track here because its execute inside tblreturntracking so not track by this model
                ];

                // Check if the new status is a valid transition from the old status
                if (isset($validTransitions[$oldStatus]) && in_array($newStatus, $validTransitions[$oldStatus])) {

                    // Find the tracking record by booking_id
                    $tracking = \App\Models\tbltracking::where('booking_id', $booking->booking_id)->first();

                    if ($tracking) {
                        // Dynamically determine the timestamp column to update
                        $column = "order_status_{$oldStatus}_to_{$newStatus}";
                        $tracking->update([
                            $column => now(), // Set the current timestamp
                        ]);
                    }
                }
            }
        });



        //when order delivered order_status in tblbooking change to 3 send email and db notification to user
        static::updated(function ($booking) {
            // Check if the order_status has changed to '3' (delivered)
            if ($booking->isDirty('order_status') && $booking->order_status == '3') {
                // Prevent sending multiple notifications for the same booking_id (same booking)
                //static $sentNotifications = [];
                $cacheKey = 'order_delivered_' . $booking->booking_id;
                // Ensure that the notification for this booking_id is only sent once
                //if (!isset($sentNotifications[$booking->booking_id])) 
                if (!cache()->has($cacheKey)) {
                    // Send the email and database notification
                    //$booking->notify(new \App\Notifications\OrderDeliveredNotification($booking));
                    //$booking->notify(new OrderDeliveredNotification($booking));
                    // Dispatch the queued notification
                    Notification::send($booking, (new OrderDeliveredNotification($booking))->onQueue('delivery'));
                    // $user->notify((new OrderDeliveredNotification($booking))->onQueue('delivery'));  this not work here like work under controller
                    // Mark as sent to prevent duplicate notifications for this booking
                    //$sentNotifications[$booking->booking_id] = true;
                    cache()->put($cacheKey, true, now()->addMinutes(5)); // Cache for 5 minutes

                }
            }
        });


        //When Order Refunded by admin user get email notification
        static::updated(function ($booking) {
            // Check if the order_status has changed to '5' (Refunded)
            if ($booking->isDirty('order_status') && $booking->order_status == '5') {
                $cacheKey = 'order_refunded_' . $booking->booking_id;
                if (!cache()->has($cacheKey)) {
                    Notification::send($booking, (new  OrderRefundNotification($booking))->onQueue('refunded'));
                    cache()->put($cacheKey, true, now()->addMinutes(5));
                }
            }
        });
    }
}
