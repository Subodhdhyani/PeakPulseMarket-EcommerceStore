<?php

namespace App\Http\Controllers;

use App\Models\tblbooking;
use App\Models\tblcart;
use App\Models\tbltracking;
use Illuminate\Http\Request;
use App\Models\tblcategorie;
use App\Models\tblreview;
use App\Models\tbluser;
use App\Models\tblfront_content;
use App\Models\tblnotification;
use App\Models\tblorderhelp;
use App\Models\tblproduct;
use App\Models\tblreturntracking;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Notifications\UserNotification;
use Illuminate\Notifications\DatabaseNotification; //databaseNotification is defualt by laravel to handle notification whatever notification class name
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; //used on admin email update


class admincontroller extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function dashboard_fetch()
    {
        // Fetch or count records from multiple tables and then display on dashboard
        $category = tblcategorie::count();
        $product = tblproduct::count();
        $new = tblbooking::where('order_status', '0')->where('payment_status', 'Successful')->count();
        $preparing = tblbooking::where('order_status', '1')->count();
        $dispatched = tblbooking::where('order_status', '2')->count();
        $delivered = tblbooking::where('order_status', '3')->count();
        $return = tblbooking::where('order_status', '6')->count();
        $cancel = tblbooking::where('order_status', '4')->count();
        $refunded = tblbooking::where('order_status', '5')->count();
        $payment_failed = tblbooking::where('payment_status', 'pending')->count();
        $user = tbluser::where('role', 'user')->count();
        $manage_review = tblreview::count();
        $manage_complain = tblorderhelp::count();               //complain = orderhelp
        //$add_notification = DatabaseNotification::count();  //databaseNotification is defualt by laravel to handle notification also import class at top
        $add_notification = DatabaseNotification::where('type', 'App\Notifications\UserNotification')->count(); //databaseNotification is defualt by laravel to handle notification also import class at top
        $response = [
            'category' => $category,
            'product' => $product,
            'new' => $new,
            'preparing' => $preparing,
            'dispatched' => $dispatched,
            'delivered' => $delivered,
            'cancel' => $cancel,
            'return' => $return,
            'refunded' => $refunded,
            'payment_failed' => $payment_failed,
            'user' => $user,
            'manage_review' => $manage_review,
            'manage_complain' => $manage_complain,
            'add_notification' => $add_notification,
        ];
        return response()->json($response);
    }
    public function category()
    {
        return view('admin.categoryadd');
    }
    public function categoryreq(Request $req)
    {
        // dd($req->all());
        $validator = Validator::make($req->all(), [
            'category_name' => 'required|string|regex:/^[a-zA-Z\s]+$/',
            'category_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }

        $record = new tblcategorie;
        $record->category_name = $req->category_name;

        $imagename = Str::random(10) . time() . "myname." . $req->file('category_image')->getClientOriginalExtension();
        $req->file('category_image')->storeAs('public/category_image', $imagename);

        $record->category_image = $imagename;
        $record->save();
        return response()->json(['status' => 'success', 'message' => 'Successfullly Inserted into Database']);
    }
    public function categorydisplay()
    {
        /* $data = tblcategorie::all();
        return response()->json(['data' => $data]);*/
        $data = tblcategorie::select('id', 'category_name', 'category_image')
            ->orderBy('id', 'asc')
            ->get();
        return response()->json(['data' => $data]);
    }
    public function categorydelete($id)
    {
        $data = tblcategorie::find($id);
        $result = $data->delete();
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Successfullly Deleted from Database']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error While Deleting the record']);
        }
    }
    public function categoryupdate($id)
    {
        //$data = tblcategorie::find($id);
        //$data = tblcategorie::select('id', 'category_name', 'category_image')->find($id);
        $data = tblcategorie::find($id, ['id', 'category_name', 'category_image']);
        return response()->json(['data' => $data]);
    }
    public function categoryupdatestore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name_update' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
            'category_image_update' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }
        $primary_id_pass_hidden = $request->hidden_id;
        $record_find_for_update = tblcategorie::find($primary_id_pass_hidden);
        if ($request->hasFile('category_image_update')) {
            $update_image_store = Str::random(10) . time() . "changed." . $request->file('category_image_update')->getClientOriginalExtension();
            $request->file('category_image_update')->storeAs('public/category_image', $update_image_store);
            $record_find_for_update->category_image = $update_image_store;
        }
        if ($request->has('category_name_update')) {
            $record_find_for_update->category_name = $request->category_name_update;
        }

        $result =  $record_find_for_update->save();
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Successfullly Update the Database']);
        }
    }
    public function product()
    {
        return view('admin.productadd');
    }
    public function product_category_fetch()
    {
        //$data = tblcategorie::select('category_name')->get();
        $data = tblcategorie::pluck('category_name');
        return response()->json(['data' => $data]);
    }
    public function product_fetch_all()
    {
        /*$data = tblproduct::all();
        return response()->json(['data' => $data]);*/
        $data = tblproduct::select('id', 'category_name', 'product_name', 'quantity', 'sale_price')
            ->orderBy('id', 'desc')
            ->get();
        return response()->json(['data' => $data]);
    }
    public function productdelete($id)
    {
        $data = tblproduct::find($id);
        $result = $data->delete();
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Successfullly Deleted from Database']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error While Deleting the record']);
        }
    }
    public function productreq(REQUEST $req)
    {
        $validator = Validator::make($req->all(), [
            'category_name' => 'required|string|regex:/^[a-zA-Z\s]+$/',
            'product_name' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/',
            'product_code' => 'required|string|regex:/^[a-zA-Z0-9]+$/',
            'weight' =>  'required|string|regex:/^[0-9]+$/',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|string|regex:/^[0-9]+$/',
            'original_price' => 'required|string|regex:/^[0-9]+$/',
            'discount' => 'required|string|regex:/^[0-9]+$/',
            'description' => 'required|max:4025',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }

        $record = new tblproduct;
        $record->category_name = $req->category_name;
        $record->product_name = $req->product_name;
        $record->product_code = $req->product_code;
        $record->weight = $req->weight;
        $record->quantity = $req->quantity;
        $record->original_price = $req->original_price;
        $record->discount = $req->discount;
        //Sale Price = Original Price - (Original Price * (Discount / 100))
        $record->sale_price = $record->original_price - ($record->original_price * ($record->discount / 100));
        $record->description = $req->description;

        $imagename = Str::random(10) . time() . "product_image." . $req->file('product_image')->getClientOriginalExtension();
        $req->file('product_image')->storeAs('public/product_image', $imagename);

        $record->product_image = $imagename;
        $record->save();
        return response()->json(['status' => 'success', 'message' => 'Successfullly Inserted into Database']);
    }
    public function productupdate($id)
    {
        //$data = tblproduct::find($id);
        $data = tblproduct::select('id', 'category_name', 'product_name', 'product_code', 'weight', 'product_image', 'quantity', 'original_price', 'discount', 'sale_price', 'description')->find($id);
        return response()->json(['data' => $data]);
    }
    public function productupdatestore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category_name_update' => 'required|string|regex:/^[a-zA-Z\s]+$/',
            'product_name_update' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/',
            'product_code_update' => 'required|string|regex:/^[a-zA-Z0-9]+$/',
            'weight_update' =>  'required|string|regex:/^[0-9]+$/',
            'product_image_update' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity_update' => 'required|string|regex:/^[0-9]+$/',
            'original_price_update' => 'required|string|regex:/^[0-9]+$/',
            'discount_update' => 'required|string|regex:/^[0-9]+$/',
            'description_update' => 'required|max:4025',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }
        $primary_id_pass_hidden = $request->hidden_id_update;
        $record_find_for_update = tblproduct::find($primary_id_pass_hidden);
        if ($request->hasFile('product_image_update')) {
            $update_image_store = Str::random(10) . time() . "changed." . $request->file('product_image_update')->getClientOriginalExtension();
            $request->file('product_image_update')->storeAs('public/product_image', $update_image_store);
            $record_find_for_update->product_image = $update_image_store;
        }
        if ($request->has('category_name_update')) {
            $record_find_for_update->category_name = $request->category_name_update;
        }
        if ($request->has('product_name_update')) {
            $record_find_for_update->product_name = $request->product_name_update;
        }
        if ($request->has('product_code_update')) {
            $record_find_for_update->product_code = $request->product_code_update;
        }
        if ($request->has('weight_update')) {
            $record_find_for_update->weight = $request->weight_update;
        }
        if ($request->has('quantity_update')) {
            $record_find_for_update->quantity = $request->quantity_update;
        }
        if ($request->has('original_price_update')) {
            $record_find_for_update->original_price = $request->original_price_update;
        }
        if ($request->has('discount_update')) {
            $record_find_for_update->discount = $request->discount_update;
        }
        $record_find_for_update->sale_price = $request->original_price_update - ($request->original_price_update * ($request->discount_update / 100));

        if ($request->has('description_update')) {
            $record_find_for_update->description = $request->description_update;
        }

        $result =  $record_find_for_update->save();
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Successfullly Update the Database']);
        }
    }

    //Common in Both User and Admin to see tracking details
    public function track_shipped_booking($id)
    {
        // Fetch booking details to check payment status if payment failed
        $booking = tblbooking::where('booking_id', $id)->first();
        if (!$booking) {
            //return redirect()->back()->with('error', 'Booking not found.');
            return view('errors.404');
        }

        // Check if payment failed
        if ($booking->payment_status == 'pending') {
            return redirect()->back()->with('error', 'No Tracking Details Found as Payment Failed.');
        }


        // Fetch tracking details from tbltracking
        $tracking = tbltracking::where('booking_id', $id)->first();
        if (!$tracking) {
            return redirect()->back()->with('error', 'No tracking details found for this booking.');
        }
        // Prepare timeline from tbltracking
        $timeline = [
            'Order Placed' => $tracking->created_at,
            'Order Cancelled By Customer Before Preparing' => $tracking->order_status_0_to_4, // Also canceled by admin
            'Order Preparing' => $tracking->order_status_0_to_1,
            'Order Cancelled By Customer After Preparing' => $tracking->order_status_1_to_4,
            'Order Dispatched' => $tracking->order_status_1_to_2,
            'Order Delivered' => $tracking->order_status_2_to_3,
            'Order Delivered Now Returning By Customer' => $tracking->order_status_3_to_6,
            'Order Return Received Refunding Soon' => $tracking->order_status_6_to_4,
            'Order Refunded Successfully' => $tracking->order_status_4_to_5,
        ];
        // Filter out null timestamps (i.e., do not display null columns)
        $filteredTimeline = array_filter($timeline);
        // Fetch only courier name and tracking number from tblreturntracking
        $returnTracking = tblreturntracking::where('booking_id', $id)
            ->select('courier_name', 'courier_tracking_number', 'return_status')
            ->first();
        // Initialize return-related messages
        $returnDetails = 'Pickup Initiate Pending'; // Default when no courier_name and courier_tracking_number are available
        if ($returnTracking) {
            if (!empty($returnTracking->courier_name) && !empty($returnTracking->courier_tracking_number)) {
                // Pickup process started if courier details are available
                $returnDetails = 'Pickup Process';
            }
            if ($returnTracking->return_status == 1) {
                // Return received if return_status is 1
                $returnDetails = 'Pickup Received';
            }
        }
        // Pass the tracking and return tracking details to the view
        return view('track_shipped_booking', compact('tracking', 'filteredTimeline', 'returnDetails', 'returnTracking'));
    }

    //Booking Detail start here
    public function newbooking()
    {
        return view('admin.newbooking');
    }
    function newbookingdisplay()
    {
        $fetch_data = tblbooking::select('id', 'booking_id', 'billing_name', 'billing_email', 'order_status')
            ->where('order_status', '0')
            ->where('payment_status', 'Successful')  //display only those booking those payment Successfully
            ->orderBy('created_at', 'asc')
            ->get();
        $data = $fetch_data->unique('booking_id')->values();;
        return response()->json(['data' => $data]);
    }
    function new_booking_detail($id)
    {
        $booking_detail = tblbooking::where('booking_id', $id)
            ->with('product:id,category_name,product_image,product_name')
            ->get();
        $status_into_text = [
            0 => 'Successfully Placed',
            1 => 'Preparing',
            2 => 'Dispatched',
            3 => 'Delivered',
            4 => 'Cancelling',
            5 => 'Refunded',
            6 => 'Returning'
        ];
        foreach ($booking_detail as $details) {
            $details->order_status_text = $status_into_text[$details->order_status] ?? 'Unknown Status';
        }
        return view('admin.new_booking_detail', compact('booking_detail'));
    }
    public function invoice_print($id)
    {
        $booking_detail = tblbooking::where('booking_id', $id)
            ->with('product:id,category_name,product_image,product_name')
            ->get();
        return view('admin.invoice_print', compact('booking_detail'));
    }
    public function newbooking_action(Request $request)
    {
        $bookingId = $request->input('bookingId');
        $action = $request->input('action');
        $booking = tblbooking::where('booking_id', $bookingId)->get();
        foreach ($booking as $booking_find) {
            if ($action === 'accept') {
                $booking_find->order_status = '1'; // Set order_status to 1 for "accepted"
                $booking_find->save();
                // $booking_find->update(['order_status' => '1']);  //1 for accept or approve then it use as a  preparing
            } elseif ($action === 'reject') {
                $booking_find->order_status = '4'; // Set order_status to 4 for "rejected"
                $booking_find->save();
                // $booking_find->update(['order_status' => '4']); //4 for reject by admin
            }
        }
        // Return a single response after updating all bookings
        return response()->json([
            'status' => $action === 'accept' ? 'accept' : 'reject',
            'message' => $action === 'accept'
                ? 'Bookings accepted successfully'
                : 'Bookings returned successfully',
        ]);
    }
    public function approved_preparing()
    {
        return view('admin.preparingbooking');
    }
    function preparing_fetch_display()
    {
        $fetch_data = tblbooking::select('id', 'booking_id', 'billing_name', 'billing_email', 'order_status')
            ->where('order_status', '1')
            ->where('payment_status', 'Successful')  //display only those booking those payment Successfully
            ->orderBy('created_at', 'asc')
            ->get();

        $data = $fetch_data->unique('booking_id')->values();;
        return response()->json(['data' => $data]);
    }
    public function preparing_detail($id)
    {
        $booking_detail = tblbooking::where('booking_id', $id)
            ->with('product:id,category_name,product_image,product_name')
            ->get();
        $status_into_text = [
            0 => 'Successfully Placed',
            1 => 'Preparing',
            2 => 'Dispatched',
            3 => 'Delivered',
            4 => 'Cancelling',
            5 => 'Refunded',
            6 => 'Returning'
        ];

        foreach ($booking_detail as $details) {
            $details->order_status_text = $status_into_text[$details->order_status] ?? 'Unknown Status';
        }

        return view('admin.preparing_detail', compact('booking_detail'));
    }
    public function prepared_detail_add_track(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bookingId' => 'required|string|regex:/^[a-zA-Z0-9_\-\s]+$/',
            'courier_name' => 'required|string|regex:/^[a-zA-Z0-9\s\.\-]+$/',
            'courier_trackid' => 'required|string|regex:/^[a-zA-Z0-9_\-]+$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }
        $bookingId = $request->bookingId;
        // Check if the booking_id not available in tbltracking when successful order placed as it create automatically by tblbooking model
        if (!tbltracking::where('booking_id', $bookingId)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tracking details for this Booking ID do not exist.',
            ]);
        }
        $data = tbltracking::where('booking_id', $bookingId)->first();
        $data->courier_name = $request->courier_name;
        $data->courier_tracking_number = $request->courier_trackid;
        //$data->order_status_1_to_2 = now();
        $result = $data->save();
        if ($result) {
            $updated_get = tblbooking::where('booking_id', $bookingId)->get();
            foreach ($updated_get as $updated) {
                $updated->order_status = '2';
                $updated->save();
            }
            return response()->json(['status' => 'success', 'message' => 'Product Dispatched Successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error while Adding Tracking Details']);
        }
    }
    public function approved_dispatched()
    {
        return view('admin.dispatchedbooking');
    }
    public function dispatched_fetch_display()
    {
        $fetch_data = tblbooking::select('id', 'booking_id', 'billing_name', 'billing_email', 'order_status')
            ->where('order_status', '2')
            ->where('payment_status', 'Successful')  //display only those booking those payment Successfully
            ->orderBy('created_at', 'asc')
            ->get();
        $data = $fetch_data->unique('booking_id')->values();;
        return response()->json(['data' => $data]);
    }
    public function booking_details_common($id)
    {
        $booking_detail = tblbooking::where('booking_id', $id)
            ->with('product:id,category_name,product_image,product_name')
            ->get();
        $status_into_text = [
            0 => 'Successfully Placed',
            1 => 'Preparing',
            2 => 'Dispatched',
            3 => 'Delivered',
            4 => 'Cancelling',
            5 => 'Refunded',
            6 => 'Returning'
        ];
        foreach ($booking_detail as $details) {
            $details->order_status_text = $status_into_text[$details->order_status] ?? 'Unknown Status';
        }
        return view('admin.booking_details_common', compact('booking_detail'));
    }
    public function approved_delivered()
    {
        return view('admin.deliveredbooking');
    }
    public function delivered_fetch_display()
    {
        $fetch_data = tblbooking::select('id', 'booking_id', 'billing_name', 'billing_email', 'order_status')
            ->where('order_status', '3')
            ->where('payment_status', 'Successful')  //display only those booking those payment Successfully
            ->orderBy('updated_at', 'desc')
            ->get();

        $data = $fetch_data->unique('booking_id')->values();;
        return response()->json(['data' => $data]);
    }
    public function cancel_booking()
    {
        return view('admin.cancelbooking');
    }

    public function cancel_fetch_display()
    {
        $fetch_data = tblbooking::select('id', 'booking_id', 'billing_name', 'billing_email', 'order_status')
            ->where('order_status', '4')
            ->where('payment_status', 'Successful')  //display only those booking those payment Successfully
            ->orderBy('updated_at', 'asc')
            ->get();

        $data = $fetch_data->unique('booking_id')->values();;
        return response()->json(['data' => $data]);
    }
    public function refunded()
    {
        return view('admin.refundedbooking');
    }
    public function refunded_fetch_display()
    {
        $fetch_data = tblbooking::select('id', 'booking_id', 'billing_name', 'billing_email', 'order_status')
            ->where('order_status', '5')
            ->where('payment_status', 'Refunded')
            ->orderBy('updated_at', 'desc')
            ->get();

        $data = $fetch_data->unique('booking_id')->values();;
        return response()->json(['data' => $data]);
    }
    public function delete_refunded_booking($id)
    {
        // Delete all rows with the given booking_id
        $deletedCount = tblbooking::where('booking_id', $id)->delete();
        if ($deletedCount > 0) {
            return response()->json(['status' => 'success', 'message' => 'Successfully Deleted Refunded Booking from Database']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No Booking Found with the Given ID']);
        }
    }
    public function return()
    {
        return view('admin.returnbooking');
    }
    public function return_fetch_display()
    {
        $fetch_data = tblbooking::select('id', 'booking_id', 'billing_name', 'billing_phone', 'order_status')
            ->where('order_status', '6')
            ->where('payment_status', 'Successful')  //display only those booking those payment Successfully
            ->orderBy('updated_at', 'asc')
            ->get();

        $data = $fetch_data->unique('booking_id')->values();;
        return response()->json(['data' => $data]);
    }
    public function return_detail_add_track(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bookingId' => 'required|string|regex:/^[a-zA-Z0-9_\-]+$/',
            'courier_name' => 'required|string|regex:/^[a-zA-Z0-9\s\.\-]+$/',
            'courier_trackid' => 'required|string|regex:/^[a-zA-Z0-9_\-]+$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }
        //Check Pickup already Generated
        $exists = tblreturntracking::where('booking_id', $request->bookingId)->exists();
        if ($exists) {
            return response()->json(['status' => 'error', 'message' => 'Return Pickup Already Initiate']);
        }
        $data = new tblreturntracking();
        $data->booking_id = $request->bookingId;
        $data->courier_name = $request->courier_name;
        $data->courier_tracking_number = $request->courier_trackid;
        $result = $data->save();
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Return Pickup Intiate']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error while Adding Return Tracking Details']);
        }
    }
    public function return_mark_received(Request $request)
    {
        $bookingId = $request->input('bookingId');
        // Check if the booking exists in tblreturntracking
        $record = tblreturntracking::where('booking_id', $bookingId)->first();
        if ($record) {
            if ($record->return_status == 0) {
                $record->return_status = '1'; // Update status to 1
                $record->save();
                return response()->json(['status' => 'success', 'message' => 'Return marked as received.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Return is already marked as received.']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Return Pickup Not Generated.First Pickup !']);
        }
    }
    public function payment_failed_booking()
    {
        return view('admin.payment_failed_booking');
    }
    public function payment_failed_fetch()
    {
        $fetch_data = tblbooking::select('id', 'booking_id', 'billing_name', 'billing_email', 'order_status')
            ->where('payment_status', 'pending')  //display only those booking those payment pending
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $fetch_data->unique('booking_id')->values();;
        return response()->json(['data' => $data]);
    }
    public function payment_failed_detail($id)
    {
        $booking_detail = tblbooking::where('booking_id', $id)
            ->with('product:id,category_name,product_image,product_name')
            ->get();
        $status_into_text = [
            0 => 'Payment Failed',
        ];

        foreach ($booking_detail as $details) {
            $details->order_status_text = $status_into_text[$details->order_status] ?? 'Unknown Status';
        }
        return view('admin.payment_failed_detail', compact('booking_detail'));
    }
    public function payment_failed_delete($id)
    {
        // Delete the given booking_id wose payment failed
        $deletedCount = tblbooking::where('booking_id', $id)->delete();
        if ($deletedCount > 0) {
            return response()->json(['status' => 'success', 'message' => 'Successfully Deleted Payment Failed Booking from Database']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No Booking Found with the Given ID']);
        }
    }
    //User details Functions
    public function user()
    {
        return view('admin.totaluser');
    }
    public function userget()
    {
        $rec = tbluser::select('id', 'name', 'email', 'phone_number', 'status')
            ->where('role', 'user')
            ->orderBy('id', 'desc')
            ->get();
        //$rec = tbluser::orderBy('id', 'desc')->get();;
        return response()->json(['rec' => $rec]);
    }
    public function deleteuser($id)
    {
        $data = tbluser::find($id);
        $result = $data->delete();
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Successfullly Deleted from Database']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error While Deleting the record']);
        }
    }
    public function user_status(REQUEST $request)
    {
        $primary_id = $request->recordId;
        $validator = Validator::make($request->all(), [
            'status' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }
        $update = tbluser::find($primary_id);
        $update->status = $request->status;
        $result = $update->save();
        if ($result && $request->status == 'block') {
            return response()->json(['status' => 'success', 'message' => 'Successfully Block the User']);
        } else if ($result && $request->status == 'unblock') {
            return response()->json(['status' => 'success', 'message' => 'Successfully Unblock the User']);
        }
    }
    //All Review Functions
    public function manage_review()
    {
        return view('admin.review');
    }
    public function manage_review_fetch()
    {
        $latest_reviews = tblreview::select('id', 'tbluser_id', 'tblproduct_id', 'booking_id', 'rating', 'review', 'status')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($review) {
                //return $review->tbluser_id . '-' . $review->tblproduct_id . '-' . $review->booking_id;
                return [$review->tbluser_id, $review->tblproduct_id, $review->booking_id];
            });
        return response()->json(['rec' => $latest_reviews->values()]);
    }
    public function manage_review_delete($id)
    {
        $data = tblreview::find($id);
        $all_review_to_that_booking_id = $data->booking_id;
        $result = tblreview::where('booking_id', $all_review_to_that_booking_id)->delete();
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Successfullly Deleted from Database']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error While Deleting the record']);
        }
    }
    public function reviewstatus(Request $request)
    {
        $primary_id = $request->recordId;
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }
        $update = tblreview::find($primary_id);
        $update->status = $request->status;
        $result = $update->save();
        if ($result && $request->status == 1) {
            return response()->json(['status' => 'success', 'message' => 'Successfully Active the Review']);
        } else if ($result && $request->status == 0) {
            return response()->json(['status' => 'success', 'message' => 'Successfully Deactive the Review']);
        }
    }
    //User Complain or helps functions
    public function complains()
    {
        return view('admin.complains');
    }

    public function complainsget()
    {
        // Fetch the latest unique records by booking_id
        $complain_detail = tblorderhelp::select('id', 'booking_id', 'subject', 'description', 'order_help_status')
            ->with('tblbooking:booking_id,billing_name,billing_phone')
            ->orderBy('created_at', 'desc') // Order by latest record
            ->get()
            ->unique('booking_id') // Keep only unique booking_id
            ->values(); // Reset keys for the collection
        return response()->json(['rec' => $complain_detail]);
    }

    public function complainsstatusupdate(Request $request)
    {
        /*$request->validate([
            'status' => 'required|integer|in:1,2',  // Only 1, or 2 are valid status values
            'recordId' => 'required|exists:tblorderhelps,id'  // check id exist in db
        ]);*/
        $validator = Validator::make($request->all(), [
            'status' => 'required|integer|in:1,2',  // Only 1, or 2 are valid status values
            'recordId' => 'required|integer|exists:tblorderhelps,id'  // check id exist in db
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'validateerror',
                'message' => $validator->errors(),
            ]);
        }

        $complain = tblorderhelp::find($request->recordId);
        $complain->order_help_status = $request->status;
        if ($complain->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update the status!'
            ]);
        }
    }
    public function complainsdetail($id)
    {
        $complain_detail = tblbooking::where('booking_id', $id)
            ->with('product:id,category_name,product_image,product_name')
            ->get();
        $status_into_text = [
            0 => 'Successfully Placed',
            1 => 'Preparing',
            2 => 'Dispatched',
            3 => 'Delivered',
            4 => 'Canceling',
            5 => 'Refunded',
            6 => 'Returning'
        ];
        foreach ($complain_detail as $complain) {
            $complain->order_status_text = $status_into_text[$complain->order_status] ?? 'Unknown Status';
        }

        //to fetch all order help related to same booking_id
        $order_help_records = tblorderhelp::where('booking_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $order_help_status_text = [
            0 => 'Open',
            1 => 'Progresss',
            2 => 'Resolved',
        ];
        foreach ($order_help_records as $helps) {
            $helps->order_help_status_text = $order_help_status_text[$helps->order_help_status] ?? 'Unknown Status';
        }

        return view('admin.complainsdetail', compact('complain_detail', 'order_help_records'));
    }
    //Manage frontend content functions
    public function frontcontent()
    {
        return view('admin.frontcontent');
    }
    public function frontcontentreq()
    {
        $data = tblfront_content::select('firstpic', 'secondpic', 'thirdpic', 'email', 'number')
            ->first();
        return response()->json(['data' => $data]);
    }
    public function frontcontentpost(REQUEST $request)
    {
        // Check which button was clicked
        $action = $request->input('button_clicked');
        // Validate form fields based on the clicked button
        if ($action === 'carousel') {
            $validator = Validator::make($request->all(), [
                'firstpic' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'secondpic' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'thirdpic' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        } elseif ($action === 'contact') {
            $validator = Validator::make($request->all(), [
                'email' => 'sometimes|email',
                'number' => 'sometimes|numeric|digits:10',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }
        $updat = tblfront_content::first();
        if ($action === 'carousel') {
            if ($request->hasFile('firstpic')) {
                $update_firstpic = Str::random(10) . time() . "update." . $request->file('firstpic')->getClientOriginalExtension();
                $request->file('firstpic')->storeAs('public/front_content', $update_firstpic);
                $updat->firstpic = $update_firstpic;
            }
            if ($request->hasFile('secondpic')) {
                $update_secondpic = Str::random(10) . time() . "update." . $request->file('secondpic')->getClientOriginalExtension();
                $request->file('secondpic')->storeAs('public/front_content', $update_secondpic);
                $updat->secondpic = $update_secondpic;
            }
            if ($request->hasFile('thirdpic')) {
                $update_thirdpic = Str::random(10) . time() . "update." . $request->file('thirdpic')->getClientOriginalExtension();
                $request->file('thirdpic')->storeAs('public/front_content', $update_thirdpic);
                $updat->thirdpic = $update_thirdpic;
            }
        }
        if ($action === 'contact') {
            if ($request->has('email')) {
                $updat->email = $request->email;
            }
            if ($request->has('number')) {
                $updat->number = $request->number;
            }
        }
        $result = $updat->save();
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Successfully Update the FrontEnd Content']);
        }
    }
    //Admin Profiles Functions
    public function profileupdate()
    {
        return view('admin.profileupdate');
    }
    public function profileupdatereq()
    {
        $data = tbluser::select('name', 'email', 'phone_number', 'address', 'profile_pic')
            ->where('role', 'admin')->first();
        return response()->json(['data' => $data]);
    }
    public function profileupdatepost(REQUEST $request)
    {
        // Get the admin user (only first admin) only work when email update
        $admin = tbluser::where('role', 'admin')->first();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|regex:/^[a-zA-Z\s]+$/',
            //'email' => 'required|email|unique:tblusers,email',
            'email' => [
                'required',
                'email',
                Rule::unique('tblusers', 'email')->ignore($admin->id), // Ignore current email if update new email
            ],
            'number' => 'required|numeric|digits:10',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:100',
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }
        $result = tbluser::where('role', 'admin')->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('number'),
            'address' => $request->input('address'),
        ]);
        if ($request->hasFile('profile')) {
            $admin_update_pic = Str::random(10) . time() . "updated." . $request->file('profile')->getClientOriginalExtension();
            $request->file('profile')->storeAs('public/admin_profile', $admin_update_pic);
            $result = tbluser::where('role', 'admin')->update(['profile_pic' => $admin_update_pic]);
        }
        if ($request->filled('password')) {
            $password = Hash::make($request->input('password'));
            $result = tbluser::where('role', 'admin')->update(['password' => $password]);
        }
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Successfullly Updated the Admin Profile']);
        }
    }
    //Admin Send or Manages Notification to users functions
    public function notification_form()
    {
        return view('admin.notification_form');
    }
    public function notification_store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'title' => 'string|required|max:255',
            'message' => 'string|required|max:500',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors()]);
        }
        $imagePath = null;
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = Str::random(10) . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('public/notification_image', $imagePath);
        }
        // Prepare notification data
        $data = [
            'title' => $request->title,
            'message' => $request->message,
            'image' => $imagePath,
        ];
        // Notify all users
        $users = tbluser::all();
        foreach ($users as $user) {
            $user->notify(new UserNotification($data));
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Notification sent successfully!',
        ]);
    }
    public function manage_notification()
    {
        return view('admin.manage_notification');
    }
    public function manage_fetch_notification()
    {
        $notifications = DB::table('notifications')
            ->where('type', 'App\Notifications\UserNotification')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification, $index) {
                // Parse the JSON data column
                $data = json_decode($notification->data, true);
                // Generate the URL for the image
                //$image_url = Storage::url('public/notification_image/' . $data['image']);
                $image_url = $data['image'] ? Storage::url('public/notification_image/' . $data['image']) : null;
                return [
                    'serial' => $index + 1,  // 1-based index for serial number not from db also created in frontend
                    'id' => $notification->id,
                    'title' => $data['title'],
                    'message' => $data['message'],
                    // 'image' => $data['image'] ?? null,
                    'image' => $image_url,
                    //'created_at' => $notification->created_at,
                    'created_at' => \Carbon\Carbon::parse($notification->created_at)->format('d-m-Y'),  // Only date
                ];
            });
        // Return the response as JSON
        return response()->json([
            'status' => 'success',
            'message' => $notifications,
        ]);
    }
    public function delete_notification($id)
    {
        $deleted = DB::table('notifications')
            ->where('id', $id)
            ->delete();
        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Notification deleted Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Error while deleting Notification',
            ]);
        }
    }




    //End of class    
}
