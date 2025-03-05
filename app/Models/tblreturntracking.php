<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\tblbooking;

class tblreturntracking extends Model
{
    use HasFactory;
    protected $table = "tblreturntrackings";
    protected $primarykey = "id";
    protected $fillable = ['booking_id', 'courier_name', 'courier_tracking_number', 'return_status'];

    protected static function booted()
    {
        static::updated(function ($returnTracking) {
            // Check if the return_status field was changeS
            if ($returnTracking->isDirty('return_status')) {
                $oldStatus = $returnTracking->getOriginal('return_status'); // Old status
                $newStatus = $returnTracking->return_status; // New status

                // Check if the value change is from 0 to 1
                if ($oldStatus === '0' && $newStatus === '1') { // Use string values for enums
                    // Update all records in tblbooking with the same booking_id and order_status of '6'
                    //$updated = tblbooking::where('booking_id', $returnTracking->booking_id)
                    //    ->where('order_status', '6')  
                    //    ->update(['order_status' => '4']); 
                    $bookings = tblbooking::where('booking_id', $returnTracking->booking_id)
                        ->where('order_status', '6')
                        ->get();

                    foreach ($bookings as $booking) {
                        $booking->order_status = '4';
                        $booking->save();  // Save each individual record


                        //as the transition 6 to 4 not track inside tbltracking as its change from here so we directly update/use here
                        $update = tbltracking::where('booking_id', $booking->booking_id)
                            ->update(['order_status_6_to_4' => now()]);
                    }


                    // if ($updated) {
                    //    Log::info('Successfully updated order_status from 6 to 4 for booking_id: ' . $returnTracking->booking_id);
                    //} else {
                    //    Log::warning('No records updated for booking_id: ' . $returnTracking->booking_id);
                    //}
                }
            }
        });
    }
}
