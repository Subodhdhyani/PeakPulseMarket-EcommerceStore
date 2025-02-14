<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\tblbooking;
//In this api,its just for testing for mark order delivered in real life we get delivery companies api to mark order delivered
class apiadmincontroller extends Controller
{
    //To Show all booking whose dispatched
    function delivery_detail()
    {
        $data = tblbooking::where('order_status', '2')
            ->select('booking_id', 'billing_name', 'billing_phone', 'billing_address', 'total_order_quantity', 'total_amount_paid', 'payment_mode', 'created_at')
            ->get();
        if ($data) {
            return response()->json(['status' => 'success', 'data' => $data]);
        } else {
            return response()->json(['status' => 'error', 'data' => 'No Data Found']);
        }
    }

    //To mark Booking as Delivered
    public function delivered_success(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|string|max:25',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => 'Enter Valid Booking Id']);
        }

        $booking_id = $request->input('booking_id');

        // Fetch bookings where booking_id matches and order_status is 2
        $bookings = tblbooking::where('booking_id', $booking_id)
            ->where('order_status', '2') // Only those booking whose dispatched 
            ->get();
        if ($bookings->isEmpty()) {
            $exists = tblbooking::where('booking_id', $booking_id)->exists();
            if ($exists) {
                return response()->json(['status' => 'error', 'message' => 'Don\'t have permission to deliver order']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'No Booking ID Found']);
            }
        }

        foreach ($bookings as $booking) {
            $booking->order_status = '3';
            $booking->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Booking Order(s) Delivered Successfully']);
    }
    
    //End of Class
}
