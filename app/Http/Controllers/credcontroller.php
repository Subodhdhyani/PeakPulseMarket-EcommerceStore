<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\tbluser;
use App\Notifications\SignupWelcomeNotification;
use Illuminate\Support\Facades\Log;
use App\Mail\ResetPasswordMail;
use App\Models\tblpasswordreset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use App\Models\TemporaryUser;  // Import the TemporaryUser class created by me to store temp data



/*class TemporaryUser
{
    use Notifiable;

    public $name;
    public $email;

    // This method is required for email notifications to work
    public function routeNotificationForMail()
    {
        return $this->email;
    }
}*/


class credcontroller extends Controller
{
    function signin()
    {
        return view('signin');
    }
    function signinreq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'email' => 'required|email',
            'email' => 'required|email|exists:tblusers,email', // Check if email exists
            'password' => 'required|string|min:7',
        ], [
            'email.exists' => 'You have not signed up yet. Please sign up first.', // Custom message
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors()]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('checkbox') && $request->checkbox == 1; // Check if "Remember Me" is enabled
        // Attempt authentication
        // if (Auth::attempt($credentials)) {
        if (Auth::guard('custom_web')->attempt($credentials, $remember)) {
            $user = Auth::guard('custom_web')->user();  //here user is method to fetch record
            // Check if user is blocked before proceeding
            if ($user->role === 'user' && $user->status !== 'unblock') {
                return response()->json(['status' => 'block_error', 'message' => 'Your account is Blocked. Please contact support.']);
            }
            //Store previous last_login in session (before update)   this display when user login and profile section of user
            $previousLogin = $user->last_login;
            Session::put('last_login', $previousLogin); // Store last login time in session
            //update the last_login field in the database when login done
            $user->update(['last_login' => now()]);
            // Check if the authenticated user is an admin
            if ($user->role === 'admin') {
                $redirectUrl = route('dashboard'); //here admin dashboard route name or
                // $redirectUrl = '/admin/dashboard';
            } elseif ($user->role === 'user') {
                $redirectUrl = route('index');
                // $redirectUrl ='/user_route_name_here'
            }
            return response()->json(['status' => 'success', 'message_redirect_url' => $redirectUrl]);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid Credentials.Please Fill Correct Detail']);
    }
    function signup()
    {
        return view('signup');
    }
    /*function signupreq(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|regex:/^[a-zA-Z]+$/',
            'email' => 'required|email',
            //'email' => 'required|email|unique:tbluser,email', // Ensure email is unique
            'mobile' => 'required|string|regex:/^[0-9]{10}$/',
            'password' => 'required|string|min:7',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'validateerror','message' => $validator->errors(),]);
        }

        $record = new tbluser;
        $record->name = $request->name;
        $record->email = $request->email;
        $record->phone_number = $request->mobile;
        $record->password = Hash::make($request->password);
        $result = $record->save();
        
        if($result){
        // Send the signup welcome email notification
        // $record->notify(new SignupWelcomeNotification());
            return response()->json(['status'=>'success','message'=>'Signup Successfully']);
        } else {
            return response()->json(['status'=>'error','message'=>'Error while Signup']);
        }
    }*/
    public function signupreq(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:tblusers,email',  // Ensure email is unique
            'mobile' => 'required|string|regex:/^[0-9]{10}$/',
            //'mobile' => 'required|regex:/^[6-9][0-9]{9}$/',
            'password' => 'required|string|min:7',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors()]);
        }
        // Store data in the session 
        $request->session()->put('signup_data', [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password), // Hash the password before storing it in the session
        ]);
        // Generate OTP
        $otp = rand(100000, 999999); // Generate a 6-digit random OTP
        // Store OTP in the session for later verification
        $request->session()->put('otp', $otp);
        // Send OTP notification via email notification
        /*$user = (object)[
           'name' => $request->name,
           'email' => $request->email,
        ];*/
        // Create a new instance of TemporaryUser and send the notification here i use class which created for temp storage
        $user = new TemporaryUser();
        $user->name = $request->name;
        $user->email = $request->email;
        try {
            // Notify user with the OTP via the SignupWelcomeNotification
            //$user->notify(new SignupWelcomeNotification($otp));    // This is now queued as que inside signup class
            $user->notify((new SignupWelcomeNotification($otp))->onQueue('signupemail'));  //gave que name same as above
            // Return success response and indicate OTP form should be shown
            return response()->json([
                'status' => 'success',
                'message' => 'OTP has been sent to your email.',
            ]);
        } catch (\Exception $e) {
            // Log error and return failure response
            Log::error('Error sending OTP email: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP. Please try again.',
            ]);
        }
    }
    public function verifyOtp(Request $request)
    {
        $otp = $request->otp;
        $storedOtp = Session::get('otp');
        $signupData = Session::get('signup_data');
        // Validate the OTP
        if ($otp != $storedOtp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP. Please try again.'
            ]);
        }
        // After verify otp now Store user data in the database  from session data
        $user = new tbluser();
        $user->name = $signupData['name'];
        $user->email = $signupData['email'];
        $user->phone_number = $signupData['mobile'];
        $user->password = $signupData['password']; //already hashed during signup
        $user->save();
        // Clear session data
        Session::forget(['otp', 'signup_data']);
        return response()->json([
            'status' => 'success',
            'message' => 'Your account has been created successfully!',
            'redirect_url' => route('signin')  // now send to signin page
        ]);
    }
    public function signout(Request $request)
    {
        // make user logout
        Auth::guard('custom_web')->logout();
        // Invalidate the session
        $request->session()->invalidate();
        // Regenerate the CSRF token to prevent reuse of the session
        $request->session()->regenerateToken();
        // Redirect to the homepage after logout
        return redirect()->route('index');
    }
    function showRequestForm()
    {
        return view('forgetpassword.password_request_form');
    }
    public function sendResetLinkEmail(Request $request)
    {
        // Validation rules and messages
        $rules = [
            'email' => 'required|email|exists:tblusers,email',
        ];

        $messages = [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.exists' => 'The email does not exist in our records.',
        ];

        // Validate the input
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }
        // Extract email from request
        $email = $request->email;
        // Generate a reset token
        $token = Str::random(64);
        // Store token in the password reset table
        $temp_data = new tblpasswordreset();
        $temp_data->email = $email;
        $temp_data->token = $token;
        $temp_data->created_at = Carbon::now();
        $temp_store = $temp_data->save();
        if ($temp_store) {
            // Encrypt the email
            $encryptedEmail = Crypt::encryptString($email);
            // Generate the reset link
            $resetLink = route('password.reset', ['token' => $token, 'email' => $encryptedEmail]);
            // Send the reset email
            // Mail::to($email)->send(new ResetPasswordMail($resetLink));
            // Dispatch the email to the queue
            //Mail::to($email)->queue(new ResetPasswordMail($resetLink));
            // Dispatch the email to the queue with a specific queue name
            // Mail::to($email)->on('resetPassword')->queue(new ResetPasswordMail($resetLink));
            Mail::to($email)->queue((new ResetPasswordMail($resetLink))->onQueue('resetpasswordlink'));
            return response()->json([
                'status' => 'success',
                'message' => 'Reset link sent successfully.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to store reset link. Please try again later.',
        ]);
    }
    public function showResetForm($token, $email)
    {
        // Decrypt the email
        $decryptedEmail = Crypt::decryptString($email);
        // Validate the token time
        $reset = tblpasswordreset::where([
            'email' => $decryptedEmail,
            'token' => $token,
        ])->latest('created_at')->first();
        //check token present or expiry of token
        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return view('forgetpassword.expire_token');
        }
        // Pass the token and email from the request to the view
        return view('forgetpassword.update_password', [
            'token' => $token,
            'email' => $email,
        ]);
    }
    // Update the password
    function reset(Request $request)
    {
        // Decrypt the email
        $decryptedEmail = Crypt::decryptString($request->email);
        // Update the request with the decrypted email for validation
        $request->merge(['email' => $decryptedEmail]);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:tblpasswordresets,email',
            'password' => 'required|min:7|confirmed',
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'validateerror', 'message' => $validator->errors(),]);
        }
        // Validate the token
        $reset = tblpasswordreset::where([
            'email' => $decryptedEmail,
            'token' => $request->token,
        ])->latest('created_at')->first();
        //check token present or expiry of token
        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid or expired reset token']);
        }
        $user = tbluser::where('email', $decryptedEmail)->first();
        $user->password = Hash::make($request->password);
        $result = $user->save();
        // Delete the token
        if ($result) {
            tblpasswordreset::where('email', $decryptedEmail)->delete();
            return response()->json(['status' => 'success', 'message' => 'Password Reset Successfully']);
        }
    }


    //End of Class
}
