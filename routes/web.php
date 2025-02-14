<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admincontroller;
use App\Http\Controllers\usercontroller;
use App\Http\Controllers\credcontroller;
use App\Http\Controllers\graphcontroller;
use App\Http\Controllers\razorpaycontroller;


//User and admin Common Route basically Credentials
Route::get('/signin', [credcontroller::class, 'signin'])->name('signin');
Route::post('/signinreq', [credcontroller::class, 'signinreq'])->name('signinreq');
Route::get('/signup', [credcontroller::class, 'signup'])->name('signup');
Route::post('/signupreq', [credcontroller::class, 'signupreq'])->name('signupreq');
Route::post('/verifyOtp', [credcontroller::class, 'verifyOtp'])->name('verifyOtp');//signup otp
Route::get('/signout', [credcontroller::class, 'signout'])->name('signout');
Route::get('password/reset', [credcontroller::class, 'showRequestForm'])->name('password.request'); //Password Reset Request Form
// Route to handle the email submission for sending the reset link via email
Route::post('password/email', [credcontroller::class, 'sendResetLinkEmail'])->name('sendResetLinkEmail');
// Route to view the password reset form when the user clicks the reset link received on email
Route::get('/password/reset/{token}/{email}', [credcontroller::class, 'showResetForm'])->name('password.reset');
// Route to handle the password update submission
Route::post('password/reset', [credcontroller::class, 'reset'])->name('password.update');


//All User Route
Route::get('/', [usercontroller::class, 'index'])->name('index'); //index or homepage
//Route::post('/search', [usercontroller::class, 'search'])->name('search');
Route::match(['GET', 'POST'], '/search', [usercontroller::class, 'search'])->name('search');//search route

Route::get('/carousel_image', [usercontroller::class, 'carousel_image'])->name('carousel_image');
Route::get('/display_category', [usercontroller::class, 'display_category'])->name('display_category');
//Recently Added category fetch in Home Page
Route::get('/fetch_pulses_home', [usercontroller::class, 'fetch_pulses_home'])->name('fetch_pulses_home');
Route::get('/fetch_pickles_home', [usercontroller::class, 'fetch_pickles_home'])->name('fetch_pickles_home');
Route::get('/fetch_snacks_home', [usercontroller::class, 'fetch_snacks_home'])->name('fetch_snacks_home');
Route::get('/fetch_spices_home', [usercontroller::class, 'fetch_spices_home'])->name('fetch_spices_home');

//Other routes
Route::get('/pickle', [usercontroller::class, 'pickles'])->name('pickles');
Route::get('/fetch_pickles', [usercontroller::class, 'fetch_pickles'])->name('fetch_pickles');
Route::get('/pulse', [usercontroller::class, 'pulses'])->name('pulses');
Route::get('/fetch_pulses', [usercontroller::class, 'fetch_pulses'])->name('fetch_pulses');
Route::get('/snack', [usercontroller::class, 'snacks'])->name('snacks');
Route::get('/fetch_snacks', [usercontroller::class, 'fetch_snacks'])->name('fetch_snacks');
Route::get('/spice', [usercontroller::class, 'spices'])->name('spices');
Route::get('/fetch_spices', [usercontroller::class, 'fetch_spices'])->name('fetch_spices');
//All Product Detail for checkout
Route::get('/product_checkout/{id}', [usercontroller::class, 'product_checkout'])->name('product_checkout');
Route::get('/product_checkout_fetch/{id?}', [usercontroller::class, 'product_checkout_fetch'])->name('product_checkout_fetch');
//Display review by fetching from db on product details page
Route::get('/review_fetch/{id?}', [usercontroller::class, 'review_fetch'])->name('review_fetch');

//User Profile Manage Route(card,my order, manage profile etc)
//User Profile Manage
Route::group(['middleware' => 'user_middleware'], function () {
  //By this Middleware the user route secure only open when the user login
  Route::get('/manage-profile', [usercontroller::class, 'manage_profile'])->name('manage_profile');
  Route::get('/manage_address', [usercontroller::class, 'manage_address'])->name('manage_address');
  Route::get('/user_profile_fetch', [usercontroller::class, 'user_profile_fetch'])->name('user_profile_fetch');
  Route::get('/user_profile_fetch_nav', [usercontroller::class, 'user_profile_fetch_nav'])->name('user_profile_fetch_nav');
  Route::post('/user_profile_update', [usercontroller::class, 'user_profile_update'])->name('user_profile_update');
  Route::post('/user_profile_update_email_verify', [usercontroller::class, 'user_profile_update_email_verify'])->name('user_profile_update_email_verify');
  Route::post('/user_address_update', [usercontroller::class, 'user_address_update'])->name('user_address_update');


  Route::post('/checkout', [razorpaycontroller::class, 'checkout'])->name('checkout');
  Route::get('/checkout_payment_page', [razorpaycontroller::class, 'checkout_payment_page'])->name('checkout_payment_page');
  Route::get('/checkout_user_detail_fetch', [razorpaycontroller::class, 'checkout_user_detail_fetch'])->name('checkout_user_detail_fetch');


  //Generate Order id(in Razorpay first generate orderid) and then store form record and then return order id to checkout_payment_page and at last the payment successfully and status changed from pending to success
  Route::post('/order', [razorpaycontroller::class, 'order'])->name('order');
  //after successful payment store payment id related data into db
  Route::post('/ordersaved', [razorpaycontroller::class, 'ordersaved'])->name('ordersaved');

  //User MyOrder
  Route::get('/myorder', [usercontroller::class, 'myorder'])->name('myorder');
  Route::post('/fetch_myorder', [usercontroller::class, 'fetch_myorder'])->name('fetch_myorder');
  Route::get('/myorder_detail/{id}', [usercontroller::class, 'myorder_detail'])->name('myorder_detail');
  Route::get('/user_invoice/{id}', [usercontroller::class, 'user_invoice'])->name('user_invoice');
  Route::post('/myorder_detail_help', [usercontroller::class, 'myorder_detail_help'])->name('myorder_detail_help');
  Route::get('/myorder_cancel_order/{id}', [usercontroller::class, 'myorder_cancel_order'])->name('myorder_cancel_order');
  Route::get('/myorder_return_order/{id}', [usercontroller::class, 'myorder_return_order'])->name('myorder_return_order');
  Route::post('/review', [usercontroller::class, 'review'])->name('review');

  //Fetch or Get Notification when user login
  Route::get('/fetch_notifications', [usercontroller::class, 'fetch_notifications'])->name('fetch_notifications');
});






//User Cart When Login and without login use Session
//Store data inside cart table or session by product_checkout view
Route::post('/add_to_cart', [usercontroller::class, 'add_to_cart'])->name('add_to_cart');
//transfer cart data from session to db
Route::post('/transfer_cart_session_to_db', [usercontroller::class, 'transfer_cart_session_to_db'])->name('transfer_cart_session_to_db');
//fetch cart
Route::get('/cart', [usercontroller::class, 'cart'])->name('cart');
Route::get('/fetch_cart', [usercontroller::class, 'fetch_cart'])->name('fetch_cart');
//count the number of items in cart at navbar
Route::get('/fetch_cart_nav_count', [usercontroller::class, 'fetch_cart_nav_count'])->name('fetch_cart_nav_count');
//delete cart for both i.e session and also from db
Route::delete('/delete_cart_item/{id?}', [usercontroller::class, 'delete_cart_item'])->name('delete_cart_item');
//Delete card item after successfully payment done and receive flash session after payment done
Route::get('/delete_cart_item_after_payment', [usercontroller::class, 'delete_cart_item_after_payment'])->name('delete_cart_item_after_payment');







//User Footer or Footer Component
Route::get('/footer_fetch', [usercontroller::class, 'footer_fetch'])->name('footer_fetch');
Route::get('/contact', [usercontroller::class, 'contactus'])->name('contactus');    //contactus unexpected error so use contact
Route::get('/aboutus', [usercontroller::class, 'aboutus'])->name('aboutus');
Route::get('/careers', [usercontroller::class, 'careers'])->name('careers');
Route::get('/ourstores', [usercontroller::class, 'ourstores'])->name('ourstores');
Route::get('/sellwithus', [usercontroller::class, 'sellwithus'])->name('sellwithus');
//for form used in footer
Route::post('/contactform', [usercontroller::class, 'contactform'])->name('contactform');
Route::post('/sellwithusform', [usercontroller::class, 'sellwithusform'])->name('sellwithusform');

Route::get('/payments', [usercontroller::class, 'payment'])->name('payment'); //payment route make unexpected error
Route::get('/shipping', [usercontroller::class, 'shipping'])->name('shipping');
Route::get('/cancellation', [usercontroller::class, 'cancellation'])->name('cancellation');
Route::get('/faq', [usercontroller::class, 'faq'])->name('faq');
Route::get('/privacy', [usercontroller::class, 'privacy'])->name('privacy');

//As this route is common to track shipped booking in both user and admin
Route::get('/track_shipped_booking/{id}', [admincontroller::class, 'track_shipped_booking'])->name('track_shipped_booking');



//All Admin Route
Route::group(['prefix' => '/admin'], function () {

  //Here apply midleware that pages/route only access whe login
  Route::group(['middleware' => 'admin_middleware'], function () {
    //these all secure by middleware only access when auth or login by admin cred   
    Route::get('/dashboard', [admincontroller::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard_fetch', [admincontroller::class, 'dashboard_fetch'])->name('dashboard_fetch');

    Route::get('/category', [admincontroller::class, 'category'])->name('category');
    Route::post('/categoryreq', [admincontroller::class, 'categoryreq'])->name('categoryreq');
    Route::get('/categorydisplay', [admincontroller::class, 'categorydisplay'])->name('categorydisplay');
    Route::delete('/categorydelete/{id?}', [admincontroller::class, 'categorydelete'])->name('categorydelete');
    Route::get('/categoryupdate/{id}', [admincontroller::class, 'categoryupdate'])->name('categoryupdate');
    Route::post('/categoryupdatestore', [admincontroller::class, 'categoryupdatestore'])->name('categoryupdatestore');

    Route::get('/product', [admincontroller::class, 'product'])->name('product');
    Route::get('/product_category_fetch', [admincontroller::class, 'product_category_fetch'])->name('product_category_fetch');
    Route::get('/product_fetch_all', [admincontroller::class, 'product_fetch_all'])->name('product_fetch_all');
    Route::post('/productreq', [admincontroller::class, 'productreq'])->name('productreq');
    Route::delete('/productdelete/{id?}', [admincontroller::class, 'productdelete'])->name('productdelete');
    Route::get('/productupdate/{id}', [admincontroller::class, 'productupdate'])->name('productupdate');
    Route::post('/productupdatestore', [admincontroller::class, 'productupdatestore'])->name('productupdatestore');



    Route::get('/newbooking', [admincontroller::class, 'newbooking'])->name('newbooking');
    Route::get('/newbookingdisplay', [admincontroller::class, 'newbookingdisplay'])->name('newbookingdisplay');
    Route::get('/new_booking_detail/{id}', [admincontroller::class, 'new_booking_detail'])->name('new_booking_detail');
    Route::get('/invoice_print/{id}', [admincontroller::class, 'invoice_print'])->name('invoice_print');
    Route::post('/newbooking_action', [admincontroller::class, 'newbooking_action'])->name('newbooking_action');
    Route::get('/preparing', [admincontroller::class, 'approved_preparing'])->name('approved_preparing');
    Route::get('/preparing_fetch_display', [admincontroller::class, 'preparing_fetch_display'])->name('preparing_fetch_display');
    Route::get('/preparing_detail/{id}', [admincontroller::class, 'preparing_detail'])->name('preparing_detail');
    Route::post('/prepared_detail_add_track', [admincontroller::class, 'prepared_detail_add_track'])->name('prepared_detail_add_track');
    Route::get('/dispatched', [admincontroller::class, 'approved_dispatched'])->name('approved_dispatched');
    Route::get('/dispatched_fetch_display', [admincontroller::class, 'dispatched_fetch_display'])->name('dispatched_fetch_display');
    //common in all dispatch , delivered , return , cancel , return to show  booking details
    Route::get('/booking_details_common/{id}', [admincontroller::class, 'booking_details_common'])->name('booking_details_common');

    //first display the dispatched detail like name,email,phone to third party just like >> dispatched_fetch_display >>route
    //after dispatched now the third party api change status from 2 to 3 i.e Delivered Successfully so this route inside api route
    Route::get('/delivered', [admincontroller::class, 'approved_delivered'])->name('approved_delivered');
    Route::get('/delivered_fetch_display', [admincontroller::class, 'delivered_fetch_display'])->name('delivered_fetch_display');
    //return done by user set order_status = 4 from 3 so this route inside user route
    Route::get('/cancel_booking', [admincontroller::class, 'cancel_booking'])->name('cancel_booking');
    Route::get('/cancel_fetch_display', [admincontroller::class, 'cancel_fetch_display'])->name('cancel_fetch_display');
    Route::post('/cancel_booking_to_refund', [razorpaycontroller::class, 'cancel_booking_to_refund'])->name('cancel_booking_to_refund');
    Route::get('/refunded', [admincontroller::class, 'refunded'])->name('refunded');
    Route::get('/refunded_fetch_display', [admincontroller::class, 'refunded_fetch_display'])->name('refunded_fetch_display');
    Route::delete('/delete_refunded_booking/{id?}', [admincontroller::class, 'delete_refunded_booking'])->name('delete_refunded_booking');
    Route::get('/return', [admincontroller::class, 'return'])->name('return');
    Route::get('/return_fetch_display', [admincontroller::class, 'return_fetch_display'])->name('return_fetch_display');
    Route::post('/return_detail_add_track', [admincontroller::class, 'return_detail_add_track'])->name('return_detail_add_track');
    Route::post('/return_mark_received', [admincontroller::class, 'return_mark_received'])->name('return_mark_received');
    Route::get('/payment_failed_booking', [admincontroller::class, 'payment_failed_booking'])->name('payment_failed_booking');
    Route::get('/payment_failed_fetch', [admincontroller::class, 'payment_failed_fetch'])->name('payment_failed_fetch');
    Route::get('/payment_failed_detail/{id}', [admincontroller::class, 'payment_failed_detail'])->name('payment_failed_detail');
    Route::delete('/payment_failed_delete/{id}', [admincontroller::class, 'payment_failed_delete'])->name('payment_failed_delete');

    Route::get('/user', [admincontroller::class, 'user'])->name('user');
    Route::get('/userget', [admincontroller::class, 'userget'])->name('userget');
    Route::delete('/deleteuser/{id?}', [admincontroller::class, 'deleteuser'])->name('deleteuser');
    Route::post('/user_status', [admincontroller::class, 'user_status'])->name('user_status');

    Route::get('/manage_review', [admincontroller::class, 'manage_review'])->name('manage_review');
    Route::get('/manage_review_fetch', [admincontroller::class, 'manage_review_fetch'])->name('manage_review_fetch');
    Route::delete('/manage_review_delete/{id?}', [admincontroller::class, 'manage_review_delete'])->name('manage_review_delete');
    Route::post('/reviewstatus', [admincontroller::class, 'reviewstatus'])->name('reviewstatus');

    Route::get('/complains', [admincontroller::class, 'complains'])->name('complains');
    Route::get('/complainsget', [admincontroller::class, 'complainsget'])->name('complainsget');
    Route::post('/complainsstatusupdate', [admincontroller::class, 'complainsstatusupdate'])->name('complainsstatusupdate');
    Route::get('/complainsdetail/{id}', [admincontroller::class, 'complainsdetail'])->name('complainsdetail');

    Route::get('/frontcontent', [admincontroller::class, 'frontcontent'])->name('frontcontent');
    Route::get('/frontcontentreq', [admincontroller::class, 'frontcontentreq'])->name('frontcontentreq');
    Route::post('/frontcontentpost', [admincontroller::class, 'frontcontentpost'])->name('frontcontentpost');

    Route::get('/profileupdate', [admincontroller::class, 'profileupdate'])->name('profileupdate');
    Route::get('/profileupdatereq', [admincontroller::class, 'profileupdatereq'])->name('profileupdatereq');
    Route::post('/profileupdatepost', [admincontroller::class, 'profileupdatepost'])->name('profileupdatepost');

    //For notification send by admin
    Route::get('/notification_form', [admincontroller::class, 'notification_form'])->name('notification_form');
    Route::post('/notification_store', [admincontroller::class, 'notification_store'])->name('notification_store');
    Route::get('/manage_notification', [admincontroller::class, 'manage_notification'])->name('manage_notification');
    Route::get('/manage_fetch_notification', [admincontroller::class, 'manage_fetch_notification'])->name('manage_fetch_notification');
    Route::delete('/delete_notification/{id?}', [admincontroller::class, 'delete_notification'])->name('delete_notification');

    //All Routes to Show Graph Details inside Admin Portal
    Route::get('/graph_dashboard', [graphcontroller::class, 'graph_dashboard'])->name('graph_dashboard');
    Route::get('/bookingGraph', [graphcontroller::class, 'bookingGraph'])->name('bookingGraph');
    Route::get('/userGraph', [graphcontroller::class, 'userGraph'])->name('userGraph');
    Route::get('/productGraph', [graphcontroller::class, 'productGraph'])->name('productGraph');
    Route::get('/helpGraph', [graphcontroller::class, 'helpGraph'])->name('helpGraph');
    Route::get('/trackGraph', [graphcontroller::class, 'trackGraph'])->name('trackGraph');
    Route::get('/returnGraph', [graphcontroller::class, 'returnGraph'])->name('returnGraph');
    Route::get('/otherGraph', [graphcontroller::class, 'otherGraph'])->name('otherGraph');
  });
});
