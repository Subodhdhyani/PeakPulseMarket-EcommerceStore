<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblcategorie;
use App\Models\tblproduct;
use App\Models\tblfront_content;
use App\Models\tblcart;
use App\Models\tblreview;
use App\Models\tblbooking;
use App\Models\tblorderhelp;
use App\Models\tbltracking;
use Illuminate\Support\Facades\Auth;
use App\Models\tbluser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; // For Date
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Mail\UserEmailUpdateVerifyOtp;

class usercontroller extends Controller
{

    function index()
    {
        return view('welcome');
    }
    //Search on navbar
    /*
    function search(Request $request)
    {
        $query = $request->input('search');
        $products = tblproduct::select('id', 'product_name', 'product_image', 'discount', 'original_price', 'sale_price')
            ->where('category_name', 'like', '%' . $query . '%')
            ->orWhere('product_name', 'like', '%' . $query . '%')
            ->paginate(15);
        if ($products->total() === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No Searched Record Found !'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ]);
    }*/
    public function search(Request $request)
    {
        $query = $request->input('search');

        $products = tblproduct::select('id', 'product_name', 'product_image', 'discount', 'original_price', 'sale_price')
            ->where('category_name', 'like', '%' . $query . '%')
            ->orWhere('product_name', 'like', '%' . $query . '%')
            ->paginate(15);

        if ($products->total() === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No Searched Record Found !'
            ]);
        }

        //Fetch ratings
        $productIds = $products->pluck('id');
        $ratings = tblreview::select('tblproduct_id', 'rating', 'booking_id', 'status')
            ->whereIn('tblproduct_id', $productIds)
            ->where('status', '1')
            ->get();

        // Inject average ratings into each product
        foreach ($products as $product) {
            $productRatings = $ratings->where('tblproduct_id', $product->id);
            $uniqueRatings = $productRatings->unique('booking_id');
            $averageRating = round($uniqueRatings->avg('rating'), 2);
            $product->average_rating = $averageRating ? $averageRating : 'No Rating';
        }


        return response()->json([
            'status' => 'success',
            'message' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ]);
    }
    //Home pages Data Display
    function carousel_image()
    {
        $fetch = tblfront_content::select('firstpic', 'secondpic', 'thirdpic')->first();
        return response()->json(['status' => 'success', 'message' => $fetch]);
    }
    function display_category()
    {
        //$fetch = tblcategorie::all();
        $fetch = tblcategorie::select('category_name', 'category_image')->get();
        return response()->json(['status' => 'success', 'message' => $fetch]);
    }
    //Home Page Recently Added Section
    /*function fetch_pulses_home()
    {
        $fetch = tblproduct::select('id', 'product_name', 'product_image', 'original_price', 'discount', 'sale_price', 'description')
            ->where('category_name', 'pulse')
            ->orderBy('created_at', 'desc') // Order by creation date in descending order
            ->limit(4) // Limit the result to the latest four
            ->get();
        return response()->json(['status' => 'success', 'message' => $fetch]);
    }*/
    public function fetch_pulses_home()
    {
        // Fetch all pulse products details
        $products = tblproduct::select('id', 'product_name', 'product_image', 'original_price', 'discount', 'sale_price', 'description')
            ->where('category_name', 'pulse')
            ->orderBy('created_at', 'desc') // Order by creation date in descending order
            ->limit(4) // Limit the result to the latest four
            ->get();

        // Fetch ratings for each product in batch
        $productIds = $products->pluck('id');  // Get the product IDs for batch fetching

        // Fetch ratings for the selected products
        $ratings = tblreview::select('tblproduct_id', 'rating', 'booking_id', 'status')
            ->whereIn('tblproduct_id', $productIds)
            ->where('status', '1')  // Only fetch active ratings approved by admin
            ->get();

        // Calculate average ratings for each product
        $products->map(function ($product) use ($ratings) {
            // Get all ratings for this product
            $productRatings = $ratings->where('tblproduct_id', $product->id);

            // Get unique ratings based on booking_id (latest rating per booking)
            $uniqueRatings = $productRatings->unique('booking_id');

            // Calculate the average rating
            $averageRating = round($uniqueRatings->avg('rating'), 2);

            // Add the average rating to the product
            $product->average_rating = $averageRating ? $averageRating : 'No Rating';  // Set default if no rating
        });

        // Return the products with their average ratings
        return response()->json([
            'status' => 'success',
            'message' => $products
        ]);
    }
    function fetch_pickles_home()
    {
        $fetch = tblproduct::select('id', 'product_name', 'product_image', 'original_price', 'discount', 'sale_price', 'description')
            ->where('category_name', 'pickle')
            ->orderBy('created_at', 'desc') // Order by creation date in descending order
            ->limit(4) // Limit the result to the latest four
            ->get();

        // Fetch ratings for each product in batch
        $productIds = $fetch->pluck('id');  // Get the product IDs for batch fetching

        // Fetch ratings for the selected products
        $ratings = tblreview::select('tblproduct_id', 'rating', 'booking_id', 'status')
            ->whereIn('tblproduct_id', $productIds)
            ->where('status', '1')  // Only fetch active ratings by admin
            ->get();

        // Calculate average ratings for each product
        $fetch->map(function ($product) use ($ratings) {
            // Get all ratings for this product
            $productRatings = $ratings->where('tblproduct_id', $product->id);

            // Get unique ratings based on booking_id (latest rating per booking)
            $uniqueRatings = $productRatings->unique('booking_id');

            // Calculate the average rating
            $averageRating = round($uniqueRatings->avg('rating'), 2);

            // Add the average rating to the product
            $product->average_rating = $averageRating ? $averageRating : 'No Rating';  // Set default if no rating
        });

        return response()->json(['status' => 'success', 'message' => $fetch]);
    }

    function fetch_snacks_home()
    {
        $fetch = tblproduct::select('id', 'product_name', 'product_image', 'original_price', 'discount', 'sale_price', 'description')
            ->where('category_name', 'snack')
            ->orderBy('created_at', 'desc') // Order by creation date in descending order
            ->limit(4) // Limit the result to the latest four
            ->get();

        // Fetch ratings for each product in batch
        $productIds = $fetch->pluck('id');  // Get the product IDs for batch fetching

        // Fetch ratings for the selected products
        $ratings = tblreview::select('tblproduct_id', 'rating', 'booking_id', 'status')
            ->whereIn('tblproduct_id', $productIds)
            ->where('status', '1')  // Only fetch active ratings by admin
            ->get();

        // Calculate average ratings for each product
        $fetch->map(function ($product) use ($ratings) {
            // Get all ratings for this product
            $productRatings = $ratings->where('tblproduct_id', $product->id);

            // Get unique ratings based on booking_id (latest rating per booking)
            $uniqueRatings = $productRatings->unique('booking_id');

            // Calculate the average rating
            $averageRating = round($uniqueRatings->avg('rating'), 2);

            // Add the average rating to the product
            $product->average_rating = $averageRating ? $averageRating : 'No Rating';  // Set default if no rating
        });

        return response()->json(['status' => 'success', 'message' => $fetch]);
    }

    function fetch_spices_home()
    {
        $fetch = tblproduct::select('id', 'product_name', 'product_image', 'original_price', 'discount', 'sale_price', 'description')
            ->where('category_name', 'spice')
            ->orderBy('created_at', 'desc') // Order by creation date in descending order
            ->limit(4) // Limit the result to the latest four
            ->get();

        // Fetch ratings for each product in batch
        $productIds = $fetch->pluck('id');  // Get the product IDs for batch fetching

        // Fetch ratings for the selected products
        $ratings = tblreview::select('tblproduct_id', 'rating', 'booking_id', 'status')
            ->whereIn('tblproduct_id', $productIds)
            ->where('status', '1')  // Only fetch active ratings by admin
            ->get();

        // Calculate average ratings for each product
        $fetch->map(function ($product) use ($ratings) {
            // Get all ratings for this product
            $productRatings = $ratings->where('tblproduct_id', $product->id);

            // Get unique ratings based on booking_id (latest rating per booking)
            $uniqueRatings = $productRatings->unique('booking_id');

            // Calculate the average rating
            $averageRating = round($uniqueRatings->avg('rating'), 2);

            // Add the average rating to the product
            $product->average_rating = $averageRating ? $averageRating : 'No Rating';  // Set default if no rating
        });

        return response()->json(['status' => 'success', 'message' => $fetch]);
    }

    //Fetch for Specific pages like pickles,spices etc
    function pickles()
    {
        return view('display_pickles');
    }
    /*function fetch_pickles()
    {
        $fetch = tblproduct::select('id', 'product_image', 'product_name', 'description', 'original_price', 'sale_price', 'discount')
            ->where('category_name', 'pickle')
            ->get();
        return response()->json(['status' => 'success', 'message' => $fetch]);
    }*/

    public function fetch_pickles()
    {
        // Fetch all products details
        $products = tblproduct::select('id', 'product_image', 'product_name', 'description', 'original_price', 'sale_price', 'discount')
            ->where('category_name', 'pickle')
            ->get();

        // Fetch ratings for each product in batch
        $productIds = $products->pluck('id');  // Get the product IDs for batch fetching

        // Fetch ratings for the selected products
        $ratings = tblreview::select('tblproduct_id', 'rating', 'booking_id', 'status')
            ->whereIn('tblproduct_id', $productIds)
            ->where('status', '1')  // Only fetch actived ratings by admin
            ->get();

        // Calculate average ratings for each product
        $products->map(function ($product) use ($ratings) {
            // Get all ratings for this product
            $productRatings = $ratings->where('tblproduct_id', $product->id);

            // Get unique ratings based on booking_id (latest rating per booking)
            $uniqueRatings = $productRatings->unique('booking_id');

            // Calculate the average rating
            $averageRating = round($uniqueRatings->avg('rating'), 2);

            // Add the average rating to the product
            $product->average_rating = $averageRating ? $averageRating : 'No Rating';  // Set default if no rating
        });

        // Return the products with their average ratings
        return response()->json([
            'status' => 'success',
            'message' => $products
        ]);
    }

    function pulses()
    {
        return view('display_pulses');
    }
    public function fetch_pulses()
    {
        // Fetch all products details
        $products = tblproduct::select('id', 'product_image', 'product_name', 'description', 'original_price', 'sale_price', 'discount')
            ->where('category_name', 'pulse')
            ->get();

        // Fetch ratings for each product in batch
        $productIds = $products->pluck('id');  // Get the product IDs for batch fetching

        // Fetch ratings for the selected products
        $ratings = tblreview::select('tblproduct_id', 'rating', 'booking_id', 'status')
            ->whereIn('tblproduct_id', $productIds)
            ->where('status', '1')  // Only fetch actived ratings by admin
            ->get();

        // Calculate average ratings for each product
        $products->map(function ($product) use ($ratings) {
            // Get all ratings for this product
            $productRatings = $ratings->where('tblproduct_id', $product->id);

            // Get unique ratings based on booking_id (latest rating per booking)
            $uniqueRatings = $productRatings->unique('booking_id');

            // Calculate the average rating
            $averageRating = round($uniqueRatings->avg('rating'), 2);

            // Add the average rating to the product
            $product->average_rating = $averageRating ? $averageRating : 'No Rating';  // Set default if no rating
        });

        // Return the products with their average ratings
        return response()->json([
            'status' => 'success',
            'message' => $products
        ]);
    }

    function snacks()
    {
        return view('display_snacks');
    }
    public function fetch_snacks()
    {
        // Fetch all products details
        $products = tblproduct::select('id', 'product_image', 'product_name', 'description', 'original_price', 'sale_price', 'discount')
            ->where('category_name', 'snack')
            ->get();

        // Fetch ratings for each product in batch
        $productIds = $products->pluck('id');  // Get the product IDs for batch fetching

        // Fetch ratings for the selected products
        $ratings = tblreview::select('tblproduct_id', 'rating', 'booking_id', 'status')
            ->whereIn('tblproduct_id', $productIds)
            ->where('status', '1')  // Only fetch actived ratings by admin
            ->get();

        // Calculate average ratings for each product
        $products->map(function ($product) use ($ratings) {
            // Get all ratings for this product
            $productRatings = $ratings->where('tblproduct_id', $product->id);

            // Get unique ratings based on booking_id (latest rating per booking)
            $uniqueRatings = $productRatings->unique('booking_id');

            // Calculate the average rating
            $averageRating = round($uniqueRatings->avg('rating'), 2);

            // Add the average rating to the product
            $product->average_rating = $averageRating ? $averageRating : 'No Rating';  // Set default if no rating
        });

        // Return the products with their average ratings
        return response()->json([
            'status' => 'success',
            'message' => $products
        ]);
    }
    function spices()
    {
        return view('display_spices');
    }
    public function fetch_spices()
    {
        // Fetch all products details
        $products = tblproduct::select('id', 'product_image', 'product_name', 'description', 'original_price', 'sale_price', 'discount')
            ->where('category_name', 'spice')
            ->get();

        // Fetch ratings for each product in batch
        $productIds = $products->pluck('id');  // Get the product IDs for batch fetching

        // Fetch ratings for the selected products
        $ratings = tblreview::select('tblproduct_id', 'rating', 'booking_id', 'status')
            ->whereIn('tblproduct_id', $productIds)
            ->where('status', '1')  // Only fetch actived ratings by admin
            ->get();

        // Calculate average ratings for each product
        $products->map(function ($product) use ($ratings) {
            // Get all ratings for this product
            $productRatings = $ratings->where('tblproduct_id', $product->id);

            // Get unique ratings based on booking_id (latest rating per booking)
            $uniqueRatings = $productRatings->unique('booking_id');

            // Calculate the average rating
            $averageRating = round($uniqueRatings->avg('rating'), 2);

            // Add the average rating to the product
            $product->average_rating = $averageRating ? $averageRating : 'No Rating';  // Set default if no rating
        });

        // Return the products with their average ratings
        return response()->json([
            'status' => 'success',
            'message' => $products
        ]);
    }

    //Fetch Product details 
    function product_checkout($id)
    {
        return view('product_checkout', ['fetched_id' => $id]);
    }
    public function product_checkout_fetch($id)
    {
        $fetch = tblproduct::select('id', 'category_name', 'product_name', 'product_image', 'weight', 'quantity', 'original_price', 'sale_price', 'discount', 'description')
            ->find($id);

        if ($fetch) {
            return response()->json(['status' => 'success', 'message' => $fetch]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No Product found with this ProductId']);
        }
    }

    //User Profile
    function manage_profile()
    {
        return view('manage_profile');
    }
    function manage_address()
    {
        return view('manage_address');
    }
    function user_profile_fetch_nav()
    {
        if (Auth::guard('custom_web')->check()) {
            // User is authenticated, get current user details
            $user = Auth::guard('custom_web')->user();
            $data = [
                'name' => $user->name,
                'profile_pic' => $user->profile_pic,
            ];
            return response()->json(['data' => $data]);
        }
        return response()->json(['data' => null]);
    }
    function user_profile_fetch()
    {
        if (Auth::guard('custom_web')->check()) {
            $user = Auth::guard('custom_web')->user();
            //return response()->json(['data' => $user]);
            return response()->json([
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'address' => $user->address,
                    'profile_pic' => $user->profile_pic,
                ]
            ]);
        }
    }
    function user_address_update(Request $request)
    {
        if (!Auth::guard('custom_web')->check()) {
            return response()->json(['status' => 'validateerror', 'message' => 'Unauthorized User to Update']);
        }
        $validator = Validator::make($request->all(), [
            'locality' => 'required|string|regex:/^[a-zA-Z\s\-]+$/',    //this regex also support space between character
            'city' => 'required|string|regex:/^[a-zA-Z\s\-]+$/',
            'pin' => 'required|numeric|digits:6',
            'state' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors()]);
        }
        $locality = $request->input('locality');
        $city = $request->input('city');
        $pin = $request->input('pin');
        $state = $request->input('state');
        $complete_address = $locality . ', ' . $city . ', ' . $pin . ', ' . $state;

        // Get the authenticated user
        $user = Auth::guard('custom_web')->user();

        // Update the authenticated user's address
        $user->address = $complete_address;
        $result = $user->save();

        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Successfully Updated the User Address']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to Update the User Address']);
        }
    }
    function user_profile_update(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::guard('custom_web')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not Authenticated',
            ]);
        }
        // Get the authenticated user
        $user = Auth::guard('custom_web')->user();
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|regex:/^[a-zA-Z\s\-]+$/',
            'email' => 'required|email',
            'number' => 'required|numeric|digits:10',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:7',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'validateerror',
                'message' => $validator->errors(),
            ]);
        }
        // Store the new email in the session temporarily
        Session::put('update_new_email', $request->input('email'));
        // Prepare data for updating other fields
        $data = [
            'name' => $request->input('name'),
            'email' => $user->email,  // Keep the old email until OTP verification
            'phone_number' => $request->input('number'),
        ];
        // Handle profile picture update
        if ($request->hasFile('profile')) {
            $profilePicName = Str::random(10) . time() . 'updated.' . $request->file('profile')->getClientOriginalExtension();
            $request->file('profile')->storeAs('public/user_profile', $profilePicName);
            $data['profile_pic'] = $profilePicName;
        }
        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }
        // Update user details in the database (other fields)
        $result = tbluser::where('id', $user->id)->update($data);
        // Check if update was successful
        if ($result) {
            // Check if email is updated and send OTP
            if ($user->email != $request->input('email')) {

                // Check if the email is already used by another user
                $existingUser = tbluser::where('email', $request->input('email'))->first();
                if ($existingUser) {
                    // Return error response if email is already in use
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Email is Already Used by Another User',
                    ]);
                }

                // Generate OTP
                $otp = rand(100000, 999999);
                // Store OTP in session
                Session::put('update_email_otp', $otp);
                // Send OTP email
                /*Mail::send('email_update_verify_otp_send', ['otp' => $otp], function ($message) use ($request) {
                    $message->to($request->input('email'))
                        ->subject('OTP for Email Update');
                });*/
                Mail::to($request->input('email'))
                    ->queue((new UserEmailUpdateVerifyOtp($otp))->onQueue('userupdateemail'));

                return response()->json([
                    'status' => 'otp_success',
                    'message' => 'OTP sent successfully to the updated email address.',
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully updated the user profile.',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update the user profile',
            ]);
        }
    }

    function user_profile_update_email_verify(Request $request)
    {
        // Validate OTP input
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|digits:6', // Assuming OTP is a 6-digit number
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP format.',
            ]);
        }
        // Retrieve the OTP stored in session
        $sessionOtp = Session::get('update_email_otp');
        $newEmail = Session::get('update_new_email');
        // Check if OTP matches
        if ($sessionOtp && $sessionOtp == $request->input('otp')) {
            // OTP is correct, now update the email address in the database
            $user = Auth::guard('custom_web')->user();
            $user->email = $newEmail; // Update email with the new one stored in session
            $user->save();
            // remove OTP and new email from session after successful verification
            Session::forget('update_email_otp');
            Session::forget('update_new_email');

            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verified Successfully.',
            ]);
        } else {
            // OTP is incorrect or expired
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired OTP.',
            ]);
        }
    }
    //Cart Sections
    function add_to_cart(Request $request)
    {
        // Check if the user is authenticated using the custom guard
        if (Auth::guard('custom_web')->check()) {
            // User is authenticated, save the cart item to the database
            $user = Auth::guard('custom_web')->user(); // Fetch the authenticated user
            $record = new tblcart();
            $record->tbluser_id = $user->id; // Use authenticated user's ID
            $record->tblproduct_id = $request->input('product_id');
            $record->quantity = $request->input('product_quantity');
            $result = $record->save();

            if ($result) {
                return response()->json(['status' => 'success', 'message' => 'Successfully Added Into Cart']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Failed to Add Into Cart']);
            }
        } else {
            // User is not authenticated, save the cart item to the session
            $cartData = $request->session()->get('cart_data', []);
            // Generate a unique auto-increment `id` for this session item 
            $nextId = count($cartData) > 0 ? max(array_column($cartData, 'id')) + 1 : 1;

            // Get data of the item being added
            $newItem = [
                'id' => $nextId, // Assign unique auto-increment ID
                'product_id' => $request->input('product_id'),
                'product_quantity' => $request->input('product_quantity'),
                'category_name' => $request->input('category_name'),
                'product_name' => $request->input('product_name'),
                'original_price' => $request->input('original_price'),
                'sale_price' => $request->input('sale_price'),
                'discount' => $request->input('discount'),
                'product_image' => $request->input('product_image')
            ];

            // Add the new item to the cart
            $cartData[] = $newItem;
            // Update the cart data in session
            $request->session()->put('cart_data', $cartData);
            return response()->json(['status' => 'success', 'message' => 'Inserted Into Session Cart']);
        }
    }
    function fetch_cart_nav_count()
    {
        if (Auth::guard('custom_web')->check()) {
            $userfetch = Auth::guard('custom_web')->user();

            // Sum the quantity of products in the cart
            $totalQuantity = tblcart::where('tbluser_id', $userfetch->id)->sum('quantity');

            return response()->json([
                'status' => 'success',
                'source' => 'database',  // this field identifies the source
                'total_quantity' => $totalQuantity
            ]);
        } else {
            // User is not authenticated, fetch cart data from the session
            $cartData = session()->get('cart_data', []);
            // Count total number of products in session
            $totalQuantity = count($cartData);

            return response()->json([
                'status' => 'success',
                'source' => 'session',  // Identifies the source
                'total_quantity' => $totalQuantity
            ]);
        }
    }

    function cart()
    {
        return view('cart');
    }
    function fetch_cart()
    {
        // Check if the user is authenticated using the custom guard
        if (Auth::guard('custom_web')->check()) {
            // User is authenticated, fetch cart data from the database
            $userfetch = Auth::guard('custom_web')->user();
            //$cartData = tblcart::where('tbluser_id', $user->id)->get();
            $cartData = tblcart::select('id', 'tbluser_id', 'tblproduct_id', 'quantity')
                ->where('tbluser_id', $userfetch->id)
                //->with('user', 'product')
                ->with(['user' => function ($query) {
                    // Select column name from user table
                    $query->select('id', 'name', 'email', 'phone_number', 'address');
                }, 'product' => function ($query) {
                    // Select column name from product table
                    $query->select('id', 'category_name', 'product_name', 'product_image', 'discount', 'sale_price', 'original_price');
                }])
                ->get();  //Relationship betweenn table used

            return response()->json([
                'status' => 'success',
                'source' => 'database',  //this field to identify the source
                'message' => $cartData
            ]);
        } else {
            // User is not authenticated, fetch cart data from the session
            $cartData = session()->get('cart_data', []);

            return response()->json([
                'status' => 'success',
                'source' => 'session',  //this field to identify the source
                'message' => $cartData
            ]);
        }
    }
    function transfer_cart_session_to_db(Request $request)
    {
        if (Auth::guard('custom_web')->check()) {
            // User is authenticated, save the cart item to the database
            $user = Auth::guard('custom_web')->user(); // Fetch the authenticated user
            // Check if there is session data to transfer to the database
            $sessionCartData = session()->get('cart_data', []);

            if (!empty($sessionCartData)) {
                // Transfer session cart data to the database
                foreach ($sessionCartData as $cartItem) {
                    $record = new tblcart();
                    $record->tbluser_id = $user->id; // Use authenticated user's ID
                    $record->tblproduct_id = $cartItem['product_id'];
                    $record->quantity = $cartItem['product_quantity'];
                    $result = $record->save();
                }

                session()->forget('cart_data');
            }
        }
    }

    //Delete cart for both session and database
    function delete_cart_item(Request $request, $id)
    {
        // Check if user is authenticated, then delete from database
        if (Auth::guard('custom_web')->check()) {

            $user = Auth::guard('custom_web')->user()->id;
            $cart_delete = tblcart::where('id', $id)->where('tbluser_id', $user)->first();
            if ($cart_delete) {
                $cart_delete->delete();
                return response()->json(['status' => 'success', 'message' => 'Item deleted from DB Cart']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Error while deleting item from DB cart']);
            }
        } else {
            // User is not authenticated, delete the specific session data
            $cartData = $request->session()->get('cart_data', []);

            // Filter out the item with the specific ID
            $updatedCartData = array_filter($cartData, function ($item) use ($id) {
                return $item['id'] != $id;
            });

            // Reindex the array to ensure the keys are sequential
            $updatedCartData = array_values($updatedCartData);

            if (count($updatedCartData) != count($cartData)) {
                // Update the cart data in the session
                $request->session()->put('cart_data', $updatedCartData);
                return response()->json(['status' => 'success', 'message' => 'Item deleted from session cart']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Error while deleting item from session cart']);
            }
        }
    }
    //Delete Cart Item After When Payment Successfully Done
    function delete_cart_item_after_payment()
    {
        $userId = Auth::guard('custom_web')->user()->id;
        // Delete cart items for the current user
        $cartItemsDeleted = tblcart::where('tbluser_id', $userId)->delete();
        if ($cartItemsDeleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Cart items deleted successfully after payment success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete cart items after payment success.'
            ]);
        }
    }
    //My Order Sections
    function myorder()
    {
        return view('myorder');
    }
    function fetch_myorder(Request $request)
    {
        $userId = Auth::guard('custom_web')->user()->id;
        $status = $request->status;
        // Base query
        $query = tblbooking::where('tbluser_id', $userId)
            ->select('id', 'booking_id', 'tblproduct_id', 'total_order_quantity', 'total_amount_paid', 'payment_status', 'order_status')
            ->with('product:id,category_name,product_image,product_name') // Fetch only necessary columns from related table
            ->orderBy('created_at', 'desc'); // Fetch by latest first (descending)
        // Add condition for payment status
        if ($status == 1) {
            $query->whereIn('payment_status', ['Successful', 'Refunded']);
        } elseif ($status == 0) {
            $query->where('payment_status', 'pending');
        }
        // Fetch data and ensure distinct booking_id
        $myorder = $query->get()->unique('booking_id');
        // Check if no orders found
        if ($myorder->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No orders found for this user',
                'data' => [],
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Orders fetched successfully',
            'data' => $myorder->values(), // Reset collection keys for proper JSON response
        ]);
    }
    function myorder_detail($id)
    {
        // Fetch the order detail using the booking id
        $orders = tblbooking::where('booking_id', $id) //details by booking id all having same booking id
            ->with('product:id,category_name,product_image,product_name') // by relationship only fetch neccessary columns
            ->get(); //retrieve all same booking id details  //for single use first();
        if ($orders->isEmpty()) {
            // If no order is found, redirect back
            return redirect()->route('myorder')->with('error', 'Error While Displaying Booking Detail');
        }
        $status_into_text = [
            0 => 'Successfull Placed',
            1 => 'Preparing',
            2 => 'Dispatched',
            3 => 'Delivered',
            4 => 'Cancelling',
            5 => 'Refunded',
            6 => 'Returning'
        ];
        //$order_status = $status_into_text[$orders->order_status] ?? 'Unknown Status';
        //return view('myorderdetail', compact('orders', 'order_status'));
        // Map the status for each order
        $orders->each(function ($order) use ($status_into_text) {
            $order->status_text = $status_into_text[$order->order_status] ?? 'Unknown Status';
        });
        return view('myorderdetail', compact('orders'));
    }
    function user_invoice($id)
    {
        $booking_detail = tblbooking::where('booking_id', $id)
            ->with('product:id,category_name,product_image,product_name')
            ->get();

        // Load the view into a variable
        $view = view('user_invoice', compact('booking_detail'))->render();

        // Generate the PDF
        $pdf = Pdf::loadHTML($view);

        // Return PDF as a response (not forced download)
        return response($pdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Invoice_' . $id . '.pdf"'); // Inline will allow it to be handled by JavaScript
    }

    function myorder_detail_help(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|regex:/^[A-Za-z0-9 ]+$/|max:30',
            'description' => 'required|regex:/^[A-Za-z0-9 ]+$/|max:125',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }
        $userId = Auth::guard('custom_web')->user()->id;
        $booking_id = $request->booking_id;
        $subject = $request->subject;
        $description = $request->description;
        $store = new tblorderhelp();
        $store->tbluser_id = $userId;
        $store->booking_id = $booking_id;
        $store->subject = $subject;
        $store->description = $description;
        $result = $store->save();
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Thank You ! We will Connect You Shortly.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Sorry ! Error While Connecting Us ']);
        }
    }
    function myorder_cancel_order($id)
    {
        $user = Auth::guard('custom_web')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated.']);
        }
        $userId = $user->id;
        $bookings = tblbooking::where('booking_id', $id)->get(); // Fetch all records with the same booking ID
        if ($bookings->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Booking not found.']);
        }
        // Check if the bookings belong to the authenticated user
        if ($bookings->first()->tbluser_id != $userId) {
            return response()->json(['status' => 'error', 'message' => 'You do not have permission to cancel these orders.']);
        }
        foreach ($bookings as $booking) {
            if ($booking->order_status == '0' || $booking->order_status == '1') {
                $booking->order_status = '4'; // Set to "Canceled"
                $booking->save();
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Orders Cancelled successfully.']);
    }

    function myorder_return_order($id)
    {
        $user = Auth::guard('custom_web')->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated.']);
        }

        $userId = $user->id;
        $bookings = tblbooking::where('booking_id', $id)->get(); // Fetch all records with the same booking ID

        if ($bookings->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Booking not found.']);
        }

        // Check if the bookings belong to the authenticated user
        if ($bookings->first()->tbluser_id != $userId) {
            return response()->json(['status' => 'error', 'message' => 'You do not have permission to cancel these orders.']);
        }
        foreach ($bookings as $booking) {
            if ($booking->order_status == '3') {
                $updated_at = Carbon::parse($booking->updated_at); // Convert updated_at to Carbon instance
                $seven_days_ago = Carbon::now()->subDays(7); // Get the date 7 days ago

                if ($updated_at->greaterThanOrEqualTo($seven_days_ago)) {
                    //$booking->update(['order_status' => '6']); // 
                    $booking->order_status = '6'; // Set the new status
                    $booking->save();
                    //we also manually update from here now automatically update inside tblbooking
                    //$update_time_3_to_6 = tbltracking::where('booking_id',$id)->first();
                    //$update_time_3_to_6->order_status_3_to_6 = now();
                    //$update_time_3_to_6->save();
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Return Policies expired / After 7 Days no return']);
                }
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Orders Return Successfully.']);
    }

    function review(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|regex:/^[a-zA-Z0-9\s]*$/',
            'product_id' => 'required',
            'booking_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }

        // Check if there is already an active old review with the same user, product, and booking
        $existingActiveReview = tblreview::where('tbluser_id', Auth::guard('custom_web')->user()->id)
            ->where('tblproduct_id', $request->product_id)
            ->where('booking_id', $request->booking_id)
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->first();
        //If there's an old active review, set its status to 0(deactive)
        if ($existingActiveReview) {
            $existingActiveReview->status = 0;
            $existingActiveReview->save();
        }

        //save review detail
        $save_review = new tblreview();
        $save_review->tbluser_id = Auth::guard('custom_web')->user()->id;
        $save_review->tblproduct_id = $request->product_id;
        $save_review->booking_id = $request->booking_id;
        $save_review->rating = $request->rating;
        $save_review->review = $request->review;
        $result = $save_review->save();
        if ($result) {
            return response()->json(['status' => 'success', 'message' => 'Thank You ! For giving your valuable time']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Sorry ! Please try later']);
        }
    }

    //Fetch review to display on product checkout page
    function review_fetch($id)
    {
        $reviews = tblreview::where('tblproduct_id', $id)
            ->where('status', 1)
            ->with(['user:id,name']) // Eager load the user relationship
            ->select('rating', 'review', 'updated_at', 'tbluser_id') // Make sure tbluser_id is selected
            ->orderBy('updated_at', 'desc')
            ->get();

        if ($reviews->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No Review Found!']);
        }

        return response()->json(['status' => 'success', 'message' => $reviews]);
    }

    //All Footer Functions
    function footer_fetch()
    {
        $firstAdmin = tblfront_content::all()->first();
        $email = $firstAdmin->email;
        $mobile = $firstAdmin->number;
        return response()->json(['status' => 'success', 'email' => $email, 'mobile' => $mobile]);
    }
    function aboutus()
    {
        return view('footer.aboutus');
    }
    function contactus()
    {
        return view('footer.contactus');
    }
    function careers()
    {
        return view('footer.careers');
    }
    function ourstores()
    {
        return view('footer.ourstores');
    }
    function sellwithus()
    {
        return view('footer.sellwithus');
    }

    function payment()
    {
        return view('footer.payment');
    }
    function shipping()
    {
        return view('footer.shipping');
    }
    function cancellation()
    {
        return view('footer.cancellation');
    }
    function faq()
    {
        return view('footer.faq');
    }
    function privacy()
    {
        return view('footer.privacy');
    }
    function contactform(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20|regex:/^[a-zA-Z\s]+$/',
            'subject' => 'required|string|max:150',
            'email' => 'required|email',
            //'number' => 'required|numeric|digits:10',
            //'number' => 'required|regex:/^[0-9]{10}$/',
            'number' => 'required|numeric|digits:10|regex:/^[0-9]{10}$/',
            'comment' => 'required|string|max:300',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }

        // If validation passes, forward the data to Formspree
        $response = Http::post('https://formspree.io/f/mayrvlkg', [
            'name' => $request->name,
            'subject' => $request->subject,
            'email' => $request->email,
            'number' => $request->number,
            'comment' => $request->comment,
        ]);

        // Check if Formspree response is successful
        if ($response->successful()) {
            return response()->json(['status' => 'success', 'message' => 'Thanks ! We will connect you shortly']);
        }

        // If Formspree fails, return error message
        return response()->json(['status' => 'error', 'message' => 'Error! While connecting us']);
    }

    function sellwithusform(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20|regex:/^[a-zA-Z\s]+$/',
            'subject' => 'required|string|max:150',
            'phone' => 'required|numeric|digits:10|regex:/^[0-9]{10}$/',
            'email' => 'required|email',
            'product' => 'required|string|max:300',
            'quantity' => 'required|numeric|min:1',
            'description' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }

        // If validation passes, forward the data to Formspree
        $response = Http::post('https://formspree.io/f/xnqewwya', [
            'name' => $request->name,
            'subject' => $request->subject,
            'phone' => $request->phone,
            'email' => $request->email,
            'product' => $request->product,
            'quantity' => $request->quantity,
            'description' => $request->description,
        ]);

        // Check if Formspree response is successful
        if ($response->successful()) {
            return response()->json(['status' => 'success', 'message' => 'Thanks ! We will connect you shortly']);
        }

        // If Formspree fails, return error message
        return response()->json(['status' => 'error', 'message' => 'Error! While connecting us']);
    }
    //Notification shown to all user who's login only
    function fetch_notifications()
    {
        // Fetch the authenticated user using the custom guard
        $user = Auth::guard('custom_web')->user();

        if ($user) {
            // Retrieve all notifications
            //$notifications = $user->notifications;
            //$notifications = $user->notifications()->latest()->take(10)->get(); 
            $notifications = $user->notifications()
                //->where('type', 'App\Notifications\UserNotification')
                ->orderBy('created_at', 'desc')->limit(10)->get();

            $formattedNotifications = $notifications->map(function ($notification) {
                return [
                    'title' => $notification->data['title'] ?? 'No Title',
                    'message' => $notification->data['message'] ?? 'No Message',
                    'image' => $notification->data['image'] ?? null,
                    //'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                    //'read_at' => $notification->read_at ? $notification->read_at->format('Y-m-d H:i:s') : null, 
                ];
            });

            return response()->json([
                'status' => 'success',
                'notifications' => $formattedNotifications,
            ]);
        }

        // If the user is not authenticated
        return response()->json([
            'status' => 'error',
            'message' => 'User not authenticated to see Notification',
        ]);
    }

    //End of Class
}
