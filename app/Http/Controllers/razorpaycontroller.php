<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\tbluser;
use App\Models\tblbooking;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;



class razorpaycontroller extends Controller
{
    public function checkout(Request $request)
    {
        $product_ids = $request->productIds;
        $total_order_quantity = $request->totalOrderQuantity;
        $total_amount_paid = $request->totalAmount;
        $sale_prices = $request->sale_prices;
        $delivery_charges = $request->delivery_charges;

        if ($product_ids && $total_order_quantity && $total_amount_paid  && $sale_prices  && $delivery_charges) {
            // Store data in session
            session([
                'product_ids' => $product_ids,
                'total_order_quantity' => $total_order_quantity,
                'total_amount_paid' => $total_amount_paid,
                'sale_prices' => $sale_prices,
                'delivery_charges' => $delivery_charges,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Successfully Pass Data for Payment', 'redirect_url' => route('checkout_payment_page')]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Insufficient Product Data for Payment']);
        }
    }

    public function checkout_payment_page()
    {
        // Retrieve session data
        $product_ids = session('product_ids');
        $total_order_quantity = session('total_order_quantity');
        $total_amount_paid = session('total_amount_paid');
        $sale_prices = session('sale_prices');
        $delivery_charges = session('delivery_charges');

        // Check if session data exists, if yes then forget i.e one time usable as the cart value change 
        $show_back_link = ($product_ids && $total_order_quantity && $total_amount_paid && $sale_prices && $delivery_charges);

        // If session data exists, clear the session to make it one-time use
        if ($show_back_link) {
            session()->forget('product_ids');
            session()->forget('total_order_quantity');
            session()->forget('total_amount_paid');
            session()->forget('sale_prices');
            session()->forget('delivery_charges');
        }

        // Return the checkout page with session data and whether the back link should be shown
        return view('checkout', compact('product_ids', 'total_order_quantity', 'total_amount_paid', 'sale_prices', 'delivery_charges', 'show_back_link'));
    }

    public function checkout_user_detail_fetch()
    {
        if (Auth::guard('custom_web')->check()) {
            // User is authenticated, get current user detail
            $user = Auth::guard('custom_web')->user();
            $data = [
                'fetched_user_id' => $user->id,
                'fetched_name' => $user->name,
                'fetched_email' => $user->email,
                'fetched_phone' => $user->phone_number,
                'fetched_address' => $user->address,
            ];
            return response()->json(['status' => 'success', 'message' => $data]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized User Details']);
        }
    }
    public function order(REQUEST $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_name' => 'required',
            'billing_user_id' => 'required',
            'billing_email' => 'required|email',
            'billing_phone' => 'required|numeric|digits:10',
            'billing_address' => 'required',
            'total_amount_paid' => 'required',
            'quantity' => 'required',
            'product_ids' => 'required',
            'sale_prices' => 'required',
            'delivery_charges' => 'required',
        ]);
        if ($validator->fails()) {
            // Return the validation errors as JSON response
            return response()->json(['status' => 'error', 'message' => $validator->errors(),]);
        }
        // Store the booking details for each product_id
        //$product_ids = $request->product_ids;  // This will be an array of product IDs
        //dd($product_ids); 
        // Convert product_ids to an array if it's a string
        $product_ids = is_array($request->product_ids) ? $request->product_ids : explode(',', $request->product_ids);
        //same for sale prices
        $sale_prices = is_array($request->sale_prices) ? $request->sale_prices : explode(',', $request->sale_prices);
        $booking_id = 'PPM_' . substr(bin2hex(random_bytes(5)), 0, 10);  // Generate a unique booking ID
        //$booking_id  = 'PPM_' . uniqid();  //less secure
        //$bookingId = 'PPM_' . (string) \Illuminate\Support\Str::uuid(); // Generate UUID
        // Ensure the product_ids and sale_prices arrays have the same length
        if (count($product_ids) !== count($sale_prices)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product IDs and sale prices must have the same number of items.',
            ]);
        }
        //Initially store the prefilled(received detail) record then the rest record store when it completed
        foreach ($product_ids as $index => $product_id) {
            $a = new tblbooking;
            //$booking_id = 'PPM_' . substr(bin2hex(random_bytes(5)), 0, 10);  //in place of receipt/booking_id pass id or any dat like mobile etc not important
            $a->booking_id = $booking_id;
            $a->billing_name = $request->billing_name;
            $a->tbluser_id = $request->billing_user_id;
            $a->billing_email = $request->billing_email;
            $a->billing_phone = $request->billing_phone;
            $a->billing_address = $request->billing_address;
            $a->total_amount_paid = $request->total_amount_paid;
            $a->total_order_quantity = $request->quantity;
            $a->delivery_charges  = $request->delivery_charges;
            $a->sale_prices =  $sale_prices[$index]; // Assign the corresponding sale price
            //$a->tblproduct_id = $request->product_ids;
            $a->tblproduct_id = $product_id;  // Store each individual product ID
            $a->save();
        }
        $amount = $request->total_amount_paid * 100; //because razorpay take as paise i.e amount*100
        $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
        //Create Order id
        $razororder = $api->order->create(array('amount' => $amount, 'currency' => 'INR',));
        //dd($razororder); //by this we see all the  order create
        $order_id = $razororder->id; //received order id
        $currency = $razororder->currency;
        //dd($order_id);
        //store order_id in db after creating
        //$upd = tblbooking::where('booking_id', $booking_id);
        //$upd->order_id = $order_id;
        //$upd->currency = $currency;
        //$upd->update();
        $upd = tblbooking::where('booking_id', $booking_id)
            ->update([
                'order_id' => $order_id,
                'currency' => $currency
            ]);

        //$upd = tblbooking::find($a->id);   //find by primary key of form storing value autocreated
        //$upd->order_id= $order_id;
        //$upd->currency= $currency;
        //$upd->update();
        //pass required field to frontend for checkout page
        return response()->json(["status" => "success", "order_id" => $order_id, "amount" => $amount, "billing_name" => $request->billing_name, "billing_email" => $request->billing_email, "billing_phone" => $request->billing_phone]);
    }

    public function ordersaved(REQUEST $request)
    {
        //dd($request->all());
        $orderid = $request->razorpay_order_id;
        $paymentid = $request->razorpay_payment_id;
        /*As this below used for single update
         $a = tblbooking::where('order_id',$orderid);
        if($a){
        $a->payment_status="Successful";
        $a->payment_mode="Prepaid/Online";
        $a->payment_id = $paymentid;
        $a->update(); $a->save();
        */
        /* As this below used for multiple update and its good but i used model trigger inside tblbooking model then used save
        $result = tblbooking::where('order_id', $orderid)
        ->update([
             'payment_status' => "Successful",
             'payment_mode' => "Prepaid/Online",
             'payment_id' => $paymentid,
        ]);
        */
        // Fetch all bookings for the given order ID
        $bookings = tblbooking::where('order_id', $orderid)->get();

        foreach ($bookings as $booking) {
            $booking->payment_status = "Successful";
            $booking->payment_mode = "Prepaid/Online";
            $booking->payment_id = $paymentid;
            $result = $booking->save();
        }
        if ($result) {
            return redirect('/cart')->with('payment_success', 'Payment Sucessfully');
        } else {
            return redirect('/cart')->with('payment_error', 'Payment Failed');
        }
    }

    function cancel_booking_to_refund(Request $request)
    {
        $record = tblbooking::where('booking_id', $request->bookingId)->first();
        $paymentId = $record->payment_id;
        $amount = $record->total_amount_paid;

        $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
        $payment = $api->payment->fetch($paymentId);

        if ($payment->refund_status !== null) {   //means if $payment->refund_status === 'full' or $payment->refund_status === 'partial' then show this
            return response()->json(['status' => 'error', 'message' => 'This payment has already been refunded.']);
        }
        if ($payment->status !== 'captured') {
            return response()->json(['status' => 'error', 'message' => 'Payment is not in a refundable State/Payment Not Done.']);
        }

        $refund = $api->payment->fetch($paymentId)->refund([
            'amount' => $amount * 100,
            'speed' => 'normal',
            'notes' => [
                'reason' => 'Refund for order cancellation'
            ]
        ]);

        if ($refund) {  //if refunded successfully on razorpay server than also modified in db
            $change_status_db = tblbooking::where('payment_id', $paymentId)->get();  //or also done by bookingId here
            foreach ($change_status_db as $updated) {
                $updated->payment_status = "Refunded";
                $updated->order_status = '5';
                $updated->save();
            }
            return response()->json(['status' => 'success', 'message' => 'Refund successful']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Refund failed !']);
        }
    }

    
//End of class
}
