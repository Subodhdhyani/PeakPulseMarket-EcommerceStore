<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cart - PeakPulseMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @include('include.favicon')
    @include('include.fontawesome')
    @include('include.bootstrap')
    @include('include.spinner')
    <style>
        body {
            background-color: whitesmoke;
            margin: 0;
            padding: 0;
        }

        .toastr-success {
            background-color: green !important;
        }

        .toastr-error {
            background-color: red !important;
        }

        /*Empty Cart Css*/
        .empty-cart {
            border: 1px solid grey;
            width: 96%;
            margin-left: 2%;
            margin-right: 2%;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
        }

        .empty-cart a {
            border-radius: 0;
        }

        .empty-cart img {
            width: 50%;
            max-height: 40vh;
            height: auto;
        }

        /* empty cart on small screen*/
        @media (max-width: 600px) {
            .empty-cart img {
                height: 10vh;
            }
        }

        /*Cart css when item inside cart full/outer structure*/
        .custom-container {
            padding: 20px;
            padding-bottom: 20vh;
        }

        .content-wrapper {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .main-content,
        .aside {
            background-color: white;
            padding: 10px;
            border: 1px solid grey;
            border-radius: 10px;
        }

        .main-content {
            width: 70%;
        }

        .aside {
            width: 30%;
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 40px);
            height: auto;
            overflow-y: auto;
        }

        .aside h6,
        .aside h5 {
            display: flex;
            justify-content: space-between;
        }

        .aside hr {
            margin: 10px 0px;
        }

        /* Styles for small devices */
        @media (max-width: 600px) {
            .content-wrapper {
                flex-direction: column;
            }

            .main-content,
            .aside {
                width: 100%;
                position: static;
                max-height: none;
                height: auto;
            }
        }

        /* Cart Item Structure Css inside the main-content */

        .container-custom-height {
            max-height: 25vh;
            height: auto;
            border: 2px solid grey;
            border-radius: 5px;
        }

        /* Remove underline and blue color from anchor tag on main container */
        a {
            text-decoration: none;
            color: inherit;
        }

        .first_section {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            height: auto;
            max-height: 100%;

        }

        .first_section img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        @media (max-width: 768px) {
            .first_section {
                height: auto;
                padding: 5px;
            }

            .first_section img {
                max-width: 100%;
                max-height: 100%;
                height: auto;
                width: auto;
            }
        }

        .second_section {
            background-color: white;
            display: flex;
            justify-content: space-between;
            height: auto;
            align-items: flex-start;
        }

        .second_section>* {
            margin-top: 5px;
        }

        .second_sub_section {
            display: flex;
            flex-direction: column;
        }

        .second_sub_section h6,
        .second_sub_section p,
        .second_sub_section select,
        .second_sub_section {
            margin: 4px;
        }

        /* Style for the Place Order button */
        .place-order-btn {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.5); /* 50% transparent white */
            position: fixed;
            bottom: 2px;
            height: 15vh;
            z-index: 9999;
            text-align: center;
        }

        .place-order-btn button {
            border-radius: 0;
            width: 50%;
        }
    </style>
</head>

<body>

    {{--navbar--}}
    <x-navbar />
    <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}
        {{--When Payment Done then from there redirect here so success/error message display here--}}
        <script>
            let delete_cart_item_after_payment = null; //initial no value and when receive flash session its value set up 
        </script>
        @if(session('payment_success'))
        <div class="alert alert-success">
            {{ session('payment_success') }}
        </div>
        <script>
            delete_cart_item_after_payment = "{{ session('payment_success') }}"; //variable declare down before condition check for delete automatically after payment
        </script>
        </script>
        @endif
        @if(session('payment_error'))
        <div class="alert alert-danger">
            {{ session('payment_error') }}
        </div>
        @endif
        <h5 class="text-success text-center mt-4">Manage Items in Cart</h5>
        {{--When No Item in Cart Initially Hide--}}
        <div class="hide_empty_cart" style="display:none;">
            <div class="empty-cart mt-4 mb-4">
                <img src="{{ asset('empty_cart.jpg') }}" alt="Empty Cart Image">
                <h5>Your cart is empty!</h5>
                <p>Add items to it now.</p>
                <a href="{{ route('index') }}" class="btn btn-warning mb-4">Shop Now</a>
            </div>

            <x-footer />
        </div>
        {{--End of No item in Cart--}}

        {{--When Item in Cart Initially Hide--}}
        <div class="custom-container" style="display:none;">
            <div class="content-wrapper">
                {{-- Main Content Start here --}}
                <div class="main-content">
                    <div class="text-primary" style="border-radius: 5px; padding: 5px; background-color:aliceblue;">
                        <h6>Deliver to: <strong class="location-class">Your Location</strong>&emsp;<a href="{{route('manage_address')}}" class="text-danger">Edit <i class="fa-solid fa-pen"></i></a></h6>
                    </div>
                    <div class="mt-3 cart_data_fetch">
                        {{-- Cart Item Template --}}
                        <div class="container mt-2 mb-2 container-custom-height cart_item_template" style="display:none;">
                            <div class="row h-100">
                                <div class="col-2 first_section">
                                    {{-- Anchor tag to see product detail--}}
                                    <a href="#" class="cart_product_checkout">
                                        <img src="" alt="image" class="img-fluid cart_image">
                                    </a>
                                </div>

                                <div class="col-10 second_section">
                                    <div class="second_sub_section">
                                        <a href="#" class="cart_product_checkout">
                                            <h6 class="product_name">Product Name</h6>
                                        </a>
                                        <p class="text-primary d-none d-md-block">Category :<strong class="category_name">Name of Category</strong></p>
                                    </div>
                                    <div class="second_sub_section">
                                        <h6><del class="text-info original-price">0</del>&nbsp;&#8377;<span class="sale-price">0</span></h6>
                                        <p class="text-success text-center discount-percentage">0</p>
                                    </div>
                                    <div class="second_sub_section">
                                        <h6 class="text-primary d-none d-md-block">Delivery by<strong class="text-danger">&nbsp;{{ date('d-m-Y', strtotime('+1 week')) }}<sup>*</sup></strong></h6>
                                    </div>
                                    <div class="second_sub_section d-flex align-items-center">
                                        <select class="form-select text-info quantity_select">
                                            <option value="1">1</option>
                                            <option value="2" disabled>2</option>
                                            <option value="3" disabled>3</option>
                                            <option value="4" disabled>4</option>
                                            <option value="5" disabled>5</option>
                                            <option value="6" disabled>6</option>
                                            <option value="7" disabled>7</option>
                                            <option value="8" disabled>8</option>
                                            <option value="9" disabled>9</option>
                                            <option value="10" disabled>10</option>
                                        </select>
                                        {{-- Anchor tag to delete cart item by primary key i.e id of tblcart --}}
                                        <a href="#" class="delete_item_cart" data-id=""><i class="fa-solid fa-trash-can fa-beat-fade fa-lg text-danger"></i></a>
                                        <!-- A anchor to store product IDs as a data attribute and not visible in view just to store all product ids for checkout -->
                                        <a href="#" class="pass_all_product_id_to_checkout" data-id=""></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Cart Item Template End --}}
                    </div>
                </div>
                {{-- Aside Start Here --}}
                <div class="aside">
                    <h5>PRICE DETAILS</h5>
                    <hr>
                    <h6><span>Original Price (<span class="item-count">0</span> items)</span><span>&#8377;<span class="total-price">0</span></span></h6>
                    <h6><span>Discount</span><span>&#8377;<span class="total-discount">0</span></h6>
                    <h6><span>Sale Price</span><span>&#8377;<span class="total-sale-price">0</span></h6>
                    <h6><span>Delivery Charges</span><span>&#8377;<span class="delivery-charges">0</span></h6>
                    <hr>
                    <h5><span>Total Amount</span><span>&#8377;<span class="final-amount">0</span></h5>
                    <hr>
                    <span class="text-success">On this Order You will save &#8377;<span class="savings">0</span></span>
                    <hr>
                    <p><i class="fa-solid fa-shield fa-beat-fade fa-lg text-success"></i>&ensp;Safe and Secure Payments.100% Authentic products.</p>
                </div>
            </div>
        </div>

        {{-- Place Order Button --}}
        <div class="place-order-btn" id="checkout_button" style="display:none;">
            <a href="#" class="btn btn-warning btn-lg mt-3 mb-3 text-light">Place Order and Pay &#8377;<span class="final-amount">0</span></a>
        </div>
    </div>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
    {{--cart item counts at navbar--}}
    <script src="{{ asset('js/nav_cart_count.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            function fetchCart() {
                $.ajax({
                    url: '{{ route("fetch_cart") }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            var fetch = response.message;
                            var source = response.source; // Check the source of the cart data

                            // Remove only items that are not the template
                            $('.cart_data_fetch').children(':not(.cart_item_template)').remove();

                            if (fetch.length === 0) {
                                $('.hide_empty_cart').show();
                                $('.place-order-btn').hide(); //as this initially hide but when setinterval call and before some data and later delete the this active
                                $('.custom-container').hide(); //as this initially hide but when setinterval call and before some data and later delete the this active
                            } else {
                                var totalPrice = 0;
                                var totalDiscount = 0;
                                var totalsaleprice = 0;
                                var deliveryCharges = 80; //  delivery charge
                                fetch.forEach(function(record) {
                                    // Set a different value based on the source
                                    if (source === 'database') {
                                        // Handle the Database source  // because here table relationship used for fetch record
                                        var $cartTemplate = $('.cart_item_template').clone().removeClass('cart_item_template').show();
                                        var imageUrl = "{{ asset('storage/product_image/') }}/" + record.product.product_image;
                                        $cartTemplate.find('.cart_image').attr('src', imageUrl);
                                        $cartTemplate.find('.category_name').text(record.product.category_name);
                                        //For small Screen the name is to big then show half name and css not work as different character have different width and they not show same for all
                                        //$cartTemplate.find('.product_name').text(record.product.product_name);
                                        let productName = record.product.product_name.trim();
                                        let truncatedName = productName.length > 4 ? productName.substring(0, 4) + ".." : productName;
                                        // Apply the  text based on screen width
                                        if (window.innerWidth <= 768) {
                                            $cartTemplate.find('.product_name').text(truncatedName);
                                        } else {
                                            $cartTemplate.find('.product_name').text(productName);
                                        }
                                        //for product detail page set product detail page link dynamically
                                        var productUrl = "{{ route('product_checkout', ':id') }}".replace(':id', record.tblproduct_id);
                                        $cartTemplate.find('.cart_product_checkout').attr("href", productUrl);

                                        $cartTemplate.find('.original-price').html('&#8377;' + record.product.original_price);
                                        $cartTemplate.find('.sale-price').text(record.product.sale_price);
                                        $cartTemplate.find('.discount-percentage').text(record.product.discount + '% off');
                                        // for delete fetch id from cart db 
                                        $cartTemplate.find('.delete_item_cart').attr('data-id', record.id);
                                        //for pass all product id to checkut pages
                                        $cartTemplate.find('.pass_all_product_id_to_checkout').attr('data-id', record.tblproduct_id);

                                        $('.cart_data_fetch').append($cartTemplate);

                                        $('.location-class').text(record.user.address)
                                        // Calculate prices inside cart database loop
                                        totalPrice += parseFloat(record.product.original_price);
                                        totalsaleprice += parseFloat(record.product.sale_price);
                                        totalDiscount += parseFloat(record.product.original_price - record.product.sale_price);
                                    } else if (source === 'session') {
                                        // Handle the session source
                                        var $cartTemplate = $('.cart_item_template').clone().removeClass('cart_item_template').show();
                                        var imageUrl = "{{ asset('storage/product_image/') }}/" + record.product_image;
                                        $cartTemplate.find('.cart_image').attr('src', imageUrl);
                                        $cartTemplate.find('.category_name').text(record.category_name);
                                        //For small Screen the name is to big then show half name and css not work as different character have different width and they not show same for all
                                        // $cartTemplate.find('.product_name').text(record.product_name);
                                        let productName = record.product_name.trim();
                                        let truncatedName = productName.length > 4 ? productName.substring(0, 4) + ".." : productName;
                                        // Apply the  text based on screen width
                                        if (window.innerWidth <= 768) {
                                            $cartTemplate.find('.product_name').text(truncatedName);
                                        } else {
                                            $cartTemplate.find('.product_name').text(productName);
                                        }
                                        $cartTemplate.find('.original-price').html('&#8377;' + record.original_price);
                                        $cartTemplate.find('.sale-price').text(record.sale_price);
                                        $cartTemplate.find('.discount-percentage').text(record.discount + '% off');
                                        //for product detail page set product detail page link dynamically
                                        var productUrl = "{{ route('product_checkout', ':id') }}".replace(':id', record.product_id);
                                        $cartTemplate.find('.cart_product_checkout').attr("href", productUrl);
                                        // for delete fetch id from session
                                        $cartTemplate.find('.delete_item_cart').attr('data-id', record.id);
                                        //for pass all product id to checkut pages
                                        $cartTemplate.find('.pass_all_product_id_to_checkout').attr('data-id', record.product_id);

                                        $('.cart_data_fetch').append($cartTemplate);
                                        // Calculate prices inside session loop
                                        totalPrice += parseFloat(record.original_price);
                                        totalsaleprice += parseFloat(record.sale_price);
                                        totalDiscount += parseFloat(record.original_price - record.sale_price);
                                    }
                                });

                                if (totalsaleprice >= 1000) {
                                    deliveryCharges = 0;
                                }
                                var finalAmount = totalsaleprice + deliveryCharges;
                                var savings = totalDiscount;

                                $('.item-count').text(fetch.length);
                                $('.total-price').text(totalPrice.toFixed(2));
                                $('.total-discount').text(totalDiscount.toFixed(2));
                                $('.total-sale-price').text(totalsaleprice.toFixed(2));
                                $('.delivery-charges').text(deliveryCharges.toFixed(2));
                                $('.final-amount').text(finalAmount.toFixed(2)); //button amount and also total amount in aside
                                $('.savings').text(savings.toFixed(2));
                                $('.custom-container').show();
                                //$('.place-order-btn').show(); //button show for payment

                                if (source === 'database') {
                                    // show button when cart fetch from db
                                    $('.place-order-btn').show();

                                } else if (source === 'session') {
                                    // Change the button to a login anchor tag button when not login 
                                    $('.place-order-btn').show();
                                    $('.place-order-btn').html('<a href="{{ route("signin") }}" class="btn btn-warning btn-lg mt-3 mb-3 text-light">Login to Place Order</a>');
                                }
                            }
                        }
                    }

                });
            }
            //call function to fetch cart details
            fetchCart();

            // Function to transfer session data  to the cart database
            function transfer_cart_session_to_db() {
                $.ajax({
                    url: '{{route("transfer_cart_session_to_db")}}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {}
                });
            }
            //Execute function when page reload
            transfer_cart_session_to_db();

            // Handle delete item from cart
            $(document).on('click', '.delete_item_cart', function(e) {
                e.preventDefault();
                var itemId = $(this).data('id');
                var $itemContainer = $(this).closest('.container-custom-height');

                $.ajax({
                    url: '/delete_cart_item/' + itemId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $itemContainer.remove();
                            toastr.success(response.message, "", {
                                "toastClass": "toastr-success"
                            });
                            // Option, Also Here refresh card complete function 
                            fetchCart();

                            fetch_cart_nav_count(); //as (this is declare and used inside navbar) but now call by js file 

                        } else if (response.status === 'error') {
                            toastr.error(response.message, "", {
                                "toastClass": "toastr-error"
                            });
                        }
                    }
                });
            });

            //Pass Products Detail to payment page
            $('#checkout_button').click(function() {
                $('.spinner-container').show(); //show spinner
                // Disable the button to prevent multiple clicks
                $(this).find('a').prop('disabled', true).addClass('disabled').css('pointer-events', 'none');
                // Get the final amount (buy price)
                var buy_price = parseFloat($('.final-amount').text()).toFixed(2);
                // Get the total order quantity (item count)
                var total_order_quantity = $('.item-count').text();
                // Create an empty array to hold the product IDs
                var product_ids = [];

                // Loop through each item in the cart and get the product IDs
                $('.pass_all_product_id_to_checkout').each(function() {
                    var product_id = $(this).data('id'); // Get the product ID  
                    // Only add valid product IDs (not null or undefined)
                    if (product_id) {
                        product_ids.push(product_id);
                    }
                });
                //console.log('Product IDs:', product_ids);  // Log to the console for debugging
                var totalsaleprice = [];
                $('.sale-price').each(function() {
                    var totalsaleprices = $(this).text(); // Get the all sale price

                    // Only add valid saleprice (not null or undefined)
                    if (totalsaleprices != 0) {
                        totalsaleprice.push(totalsaleprices);
                    }
                });
                var deliveryCharges = $('.delivery-charges').text();
                // Now make the AJAX request to send the data to the backend
                $.ajax({
                    url: '{{ route("checkout") }}', // This is the route that will handle the checkout
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for security
                    },
                    data: {
                        totalAmount: buy_price, // Total amount to be paid
                        totalOrderQuantity: total_order_quantity, // Total quantity of items in the cart
                        productIds: product_ids, // Array of product IDs
                        sale_prices: totalsaleprice, // Array of sale prices
                        delivery_charges: deliveryCharges
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Redirect to the URL returned by the backend
                            window.location.href = response.redirect_url; // Redirect to the checkout page URL
                            $('.spinner-container').hide(); //hide the spinner
                        } else if (response.status === 'error') {
                            toastr.error(response.message, "Error", {
                                "toastClass": "toastr-error"
                            });
                            // Re-enable the button in case of an error
                            $('#checkout_button').find('a').prop('disabled', false).removeClass('disabled').css('pointer-events', '');
                            $('.spinner-container').hide(); //hide the spinner
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong. Please try again.', "Error", {
                            "toastClass": "toastr-error"
                        });
                        // Re-enable the button if the request fails
                        $('#checkout_button').find('a').prop('disabled', false).removeClass('disabled').css('pointer-events', '');
                        $('.spinner-container').hide(); //hide the spinner
                    }

                });
            });

            //Delete Cart Data(only when Login User) When Payment Successfully Done [flash session(payment_success & payment_error) received after payment successful] at top flash session received from backended 
            if (delete_cart_item_after_payment !== null) {
                $.ajax({
                    url: '{{ route("delete_cart_item_after_payment") }}',
                    type: 'GET',
                    /*success: function(response) { //here this message dispay not required as i already dispay at top by flash session and this display the item succesfully deleted
                        if (response.status === 'success') {
                            toastr.success(response.message, '', {
                                "toastClass": "toastr-success"
                            });
                        } else if (response.status === 'error') {
                            toastr.error(response.message, '', {
                                "toastClass": "toastr-error"
                            });
                        }
                    },*/
                    error: function() {
                        toastr.error('An error occurred while clearing the cart after Order Successful Placed.', '', {
                            "toastClass": "toastr-error"
                        });
                    },
                    success: function() {
                        //call function to fetch cart details
                        fetchCart();
                        fetch_cart_nav_count(); //as (this is declare and used inside navbar) but now call by js file 
                    }
                });
            }
        });
    </script>
</body>

</html>