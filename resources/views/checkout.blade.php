<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - PeakPulseMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @include('include.favicon')
    @include('include.fontawesome')
    @include('include.bootstrap')
    @include('include.spinner')
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 40px;
        }

        .payment-heading {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .payment-container {
            width: 100%;
            max-width: 350px;
            padding: 20px;
            max-height: 45vh;
            height: auto;
            background-color: #fff;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .btn-disable {
            cursor: not-allowed;
        }

        .toastr-error {
            background-color: red !important;
        }
    </style>
</head>

<body class="bg-warning">
    <h1 class="payment-heading">Peak Pulse Market</h1>

    @if(!$show_back_link)
    <!-- If session data is missing, then Go Back after some time -->
    <div class="payment-container">
        <h5 class="text-danger">Automatically Redirecting Back to Cart after 5 seconds.</h5>
        <a href="{{ route('cart') }}" class="btn btn-warning w-100 mt-4">Go Back</a>
    </div>
    <script>
        // Automatically redirect to cart after 5 seconds if session has expired
        setTimeout(function() {
            window.location.href = "{{ route('cart') }}"; // Redirect to the cart page
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
    @else
    <!-- If session data exists, show the payment details -->
    <div class="payment-container">
        <form autocomplete="off">
            <h5 class="text-danger">You Are Paying ₹ <span class="total_amount_paid">{{ $total_amount_paid }}</span></h5>

            <h6>Payment Modes</h6>
            <div class="mt-4">
                <button class="btn btn-warning w-100 btn-disable" id="payCodBtn" disabled><i class="fa-regular fa-circle-xmark"></i> COD (Cash on Delivery)</button>
                <p class="text-danger">Sorry, Temporarily COD Not Accepted</p>
                <button class="btn btn-success w-100 text-dark mb-4" id="payOnlineBtn"><i class="fas fa-lock"></i> Pay Online</button>
            </div>
    </div>
    <input type="hidden" class="quantity" id="quantity" name="quantity" value="{{ $total_order_quantity }}">
    <input type="hidden" class="product_ids" id="product_ids" name="product_ids" value="{{ implode(',', $product_ids) }}">{{--"{{ $product_ids[1] }}"--}}
    <input type="hidden" class="delivery_charges" id="delivery_charges" name="delivery_charges" value="{{ $delivery_charges }}">
    <input type="hidden" class="sale_prices" id="sale_prices" name="sale_prices" value="{{ implode(',', $sale_prices) }}">
    <h6 class="text-danger mt-4">Please Don't Refresh the Page ...</h6>
    </form>
    @endif
    {{--Razorpay Payment Page open after order id receive by ajax Order id receive after form post then from controller we get --}}
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            // Declare billing details globally
            var billing_user_id, billing_name, billing_email, billing_phone, billing_address;

            // Fetch billing details from server
            $.ajax({
                url: '{{ route("checkout_user_detail_fetch") }}',
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        var data = response.message;
                        billing_user_id = data.fetched_user_id;
                        billing_name = data.fetched_name;
                        billing_email = data.fetched_email;
                        billing_phone = data.fetched_phone;
                        billing_address = data.fetched_address;
                    } else if (response.status === 'error') {
                        toastr.error(response.message, "", {
                            "toastClass": "toastr-error"
                        });
                    }
                }
            });

            $('#payOnlineBtn').click(function(e) {
                e.preventDefault();
                $('#payOnlineBtn').prop('disabled', true); // Disable the button
                $('.spinner-container').show(); //show spinner

                // Get the total amount paid, quantity, and product IDs from hidden fields
                var total_amount_paid = $('.total_amount_paid').text(); // Assuming total amount is in this class
                var quantity = $('#quantity').val(); // Get the total quantity
                var product_ids = $('#product_ids').val(); // Get the product IDs
                var delivery_charges = $('#delivery_charges').val();
                var sale_prices = $('#sale_prices').val();

                // Check if billing information is available
                if (!billing_user_id || !billing_name || !billing_email || !billing_phone || !billing_address) {
                    toastr.error("Billing information is missing. Please reload the page.", "", {
                        "toastClass": "toastr-error"
                    });
                    // Re-enable the button and hide the spinner if billing info is missing
                    $('#payOnlineBtn').prop('disabled', false);
                    $('.spinner-container').hide();
                    return;
                }

                // Prepare the data object to send in the AJAX request
                var paymentData = {
                    billing_user_id: billing_user_id,
                    billing_name: billing_name,
                    billing_email: billing_email,
                    billing_phone: billing_phone,
                    billing_address: billing_address,
                    total_amount_paid: total_amount_paid,
                    quantity: quantity,
                    product_ids: product_ids,
                    sale_prices: sale_prices,
                    delivery_charges: delivery_charges
                };

                // Send the data to the server for payment processing
                $.ajax({
                    url: "{{route('order')}}",
                    type: 'POST',
                    data: paymentData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for security
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            paynow(response.order_id, response.amount, response.billing_name, response.billing_email, response.billing_phone);
                        } else if (response.status === 'error') {
                            toastr.error('Error While Ordering', "", {
                                "toastClass": "toastr-error"
                            });
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong. Please try again.', "", {
                            "toastClass": "toastr-error"
                        });
                    },
                    complete: function() {
                        // Re-enable the button and hide the spinner after the request completes
                        $('#payOnlineBtn').prop('disabled', false);
                        $('.spinner-container').hide();
                    }
                });
            });
        });


        //function for pass detail to checkout page
        function paynow(razororderid, amount, name, email, contact) {
            var options = {
                "key": "{{env('RAZORPAY_KEY_ID')}}", // Enter the Key ID generated from the Dashboard
                "amount": amount, //pass through backend order id created controller
                "currency": "INR",
                "name": "Peak Pulse Market", //your business name
                "description": "Himalayan Hues",
                "image": "https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/razorpay-icon.png",
                "order_id": razororderid, //pass through backend
                "callback_url": "{{ route('ordersaved') }}",

                "prefill": {
                    "name": name, //"double_curly_bracket Auth::user->name() double_curly_bracket"if login in php inside pass then not reuired to pass from backend
                    "email": email,
                    "contact": contact //If given here so pass from backend order id created 
                },
                "notes": {
                    "address": "Peak Pulse Market "
                },
                "theme": {
                    "color": "#0dcaf0"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();

        }
    </script>
</body>

</html>