<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Details - PeakPulseMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @include('include.favicon')
    @include('include.fontawesome')
    @include('include.bootstrap')
    @include('include.spinner')
    <style>
        body {
            background-color: whitesmoke;
        }

        .container {
            background-color: white;
            max-height: 35vh;
            height: auto;
            border: 2px solid grey;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        .hide_anchor_style {
            text-decoration: none;
            color: inherit;
        }

        .first_section {
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
        }

        .toastr-success {
            background-color: green !important;
        }

        .toastr-error {
            background-color: red !important;
        }

        .stars {
            display: flex;
            direction: row;
            cursor: pointer;
            justify-content: center;
        }

        .stars i {
            font-size: 30px;
            color: #ddd;
        }

        .stars i.active {
            color: gold;
        }

        @media screen and (max-width: 600px) {
            .track-btn {
                font-size: 12px;
                padding: 3px 10px;
                border-radius: 10px;
            }
        }
    </style>
</head>

<body>

    {{--navbar--}}
    <x-navbar />
    <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}

        @if (session('error'))
        <div class="alert alert-danger mt-2 text-dark">
            {{ session('error') }}
        </div>
        @endif

        <div class="container mt-2 mb-2">
            <h6 class="text-center text-success">Booking id : <span id="booking_id">{{$orders->first()->booking_id}}</span></h6>
            <h6 class="text-center text-success">This Booking id Contains total <span class="text-danger">{{$orders->first()->total_order_quantity}} items.</span></h6>
            <div class="row h-100">
                <div class="col-5">
                    <h5 class="d-none d-md-block">Delivery Address</h5>
                    <h6>{{$orders->first()->billing_name}}</h6>
                    <h6>{{$orders->first()->billing_address}}</h6>
                    <h6 class="d-none d-md-block"><strong>Phone :</strong>&ensp;{{$orders->first()->billing_phone}}</h6>
                </div>
                <div class="col-2">
                    <h5 class="d-none d-md-block text-center">Total Amount & Items</h5>
                    <h6 class="text-center">&#8377; {{$orders->first()->total_amount_paid}}<span class="text-danger"> ({{$orders->first()->total_order_quantity}} items)</span></h6>
                    <h5 class="text-center text-danger mt-2">
                        <a href="{{ route('track_shipped_booking', ['id' => $orders->first()->booking_id]) }}"
                            style="text-decoration: none; font-family: 'Dancing Script', cursive; font-weight: bold; border: 1px solid currentColor; border-radius: 20px; padding: 5px 15px; display: inline-block;"
                            class="text-danger track-btn"
                            title="Track Booking">Track</a>
                    </h5>
                </div>
                @if($orders->first()->payment_status !== 'pending' && $orders->first()->payment_status === 'Successful')
                <div class="col-5">
                    <h5 class="d-none d-md-block text-center">More Actions</h5>
                    @if($orders->first()->status_text === 'Successfull Placed' || $orders->first()->status_text === 'Preparing' )
                    <div class="text-center">
                        <a href="#" id="cancel_order_click" class="hide_anchor_style text-primary">Cancel <i class="fa-regular fa-circle-left fa-bounce fa-lg"></i></a>
                    </div>
                    @elseif($orders->first()->status_text === 'Delivered')
                    <div class="text-center">
                        <a href="#" id="return_order_click" class="hide_anchor_style text-primary">Return <i class="fa-regular fa-circle-left fa-bounce fa-lg"></i></a>
                    </div>
                    @elseif($orders->first()->status_text === 'Cancelling' || $orders->first()->status_text === 'Returning')
                    <div class="text-center">
                        <h6 class="text-danger">Return in Progress.Refunding soon <i class="fa-regular fa-circle-left fa-bounce fa-lg text-primary"></i></h6>
                    </div>
                    @elseif($orders->first()->status_text === 'Refunded')
                    <div class="text-center">
                        <h6 class="text-danger">Refund Initiated <i class="fa-regular fa-circle-left fa-bounce fa-lg text-primary"></i></h6>
                    </div>
                    @else
                    <div class="text-center">
                        <h6 class="text-danger">Order can't be canceled after shipping, but you can easily return it. <i class="fa-regular fa-circle-left fa-bounce fa-lg text-primary"></i></h6>
                    </div>
                    @endif

                    @if($orders->first()->status_text === 'Delivered')
                    <div class="text-center mt-2">
                        {{--<a href="{{ route('user_invoice',['id' => $orders->first()->booking_id]) }}" class="hide_anchor_style text-primary">Invoice <i class="fa-solid fa-arrow-down fa-bounce fa-lg"></i></a>--}}
                        <a href="#" id="invoice_download_on_click" class="hide_anchor_style text-primary">Invoice <i class="fa-solid fa-arrow-down fa-bounce fa-lg"></i></a>
                    </div>
                    @else
                    <div class="text-center">
                        <h6 class="text-danger">Invoice available after delivery. <i class="fa-solid fa-arrow-down fa-bounce fa-lg text-primary"></i></h6>
                    </div>
                    @endif
                </div>
                @endif

            </div>
        </div>


        @foreach($orders as $order)
        <div class="container mt-2 mb-2">
            <div class="row h-100">
                <div class="col-2 first_section">
                    <a href="{{ route('product_checkout', ['id' => $order->product->id]) }}" class="hide_anchor_style"><img src="{{ asset('storage/product_image/' . $order->product->product_image) }}" alt="product_image" class="img-fluid"></a>
                </div>
                <div class="col-4 second_section">
                    <h5 class="mt-4"><a href="{{ route('product_checkout', ['id' => $order->product->id]) }}" class="hide_anchor_style">{{ ucwords($order->product->product_name)}}</a></h5>
                    <p class="text-primary d-none d-md-block">Category : {{ ucwords($order->product->category_name) }}</p>
                    <p class="text-primary">Seller : Peak Pulse Market</p>
                    <h5>&#8377; {{$order->sale_prices}} <span class="text-danger">( Product Id : {{$order->tblproduct_id}} )</span> </h5>
                </div>
                @if($order->payment_status !== 'pending' && $order->payment_status === 'Successful' || $order->payment_status === 'Refunded')
                <div class="col-6 third_section">
                    <h5 class="mt-4">Having Issue with this Order</h5>
                    <h6 class="text-primary">
                        {{-- Anchor trigger modal --}}
                        <a href="#" class="hide_anchor_style" data-bs-toggle="modal" data-bs-target="#product_need_help_model">Need Help <i class="fa-regular fa-circle-question fa-bounce fa-lg"></i>
                        </a>
                    </h6>
                    <p class="text-success">Status : <strong>{{$order->status_text}}<span class="d-none d-sm-inline"> on {{$order->updated_at->format('d-m-Y h:i A')}}</span></strong></p>
                    @if($order->status_text === 'Delivered' || $order->status_text === 'Returning' || $order->status_text === 'Refunded')
                    <h6 class="text-primary">
                        <a href="#" class="hide_anchor_style" data-bs-toggle="modal" data-bs-target="#product_review_model" data-product-id="{{ $order->product->id }}">Review <i class="fa-regular fa-comment-dots fa-bounce fa-lg"></i></a>
                    </h6>
                    @else
                    <h6 class="text-primary">
                        <a href="javascript:void(0);" style="cursor: not-allowed; text-decoration: none;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="After Delivery">Review <i class="fa-regular fa-comment-dots fa-bounce fa-lg"></i></a>
                    </h6>
                    @endif

                </div>

                @elseif($order->payment_status === 'pending' && $order->payment_status !== 'Successful' && $order->payment_status !== 'Refunded')
                <div class="col-6 third_section">
                    <p class="text-success mt-4">Status : <strong>Payment failed <span class="d-none d-sm-inline">on {{$order->updated_at->format('d-m-Y h:i A')}}</span></strong></p>
                    <h6 class="text-primary">
                        Sorry Payment Failed ! Don't worry if amount debited,the amount refunded to source within 2-4 working days.
                    </h6>
                </div>
                @endif
            </div>
        </div>

        {{--Now this can, first fetch from anchor where click and then store inside review model and then send to form and clear from model <input type="hidden" id="product_id" name="product_id "value="{{ $order->product->id}}">--}}
        @endforeach



        {{--Model Open for Need Help on Anchor trigger--}}
        <div class="modal fade" id="product_need_help_model" tabindex="-1" aria-labelledby="product_need_help_model" aria-hidden="true">
            <div class="modal-dialog modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Provide Details. We will Connect You Shortly</h1>
                        <button type="button" data-bs-dismiss="modal"><i class="fa-solid fa-xmark fa-bounce fa-xl text-primary"></i></button>
                    </div>
                    <form autocomplete="off" id="need_help">
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="subject" name="subject" value="{{old('subject')}}" placeholder="Enter Subject" required>
                                <label for="subject">Subject</label>
                                <span class="orderhelp" id="subject_error" style="color: red;"></span>
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Enter Description" id="description" name="description" style="height: 100px" required>{{ old('description') }}</textarea>
                                <label for="description">Description</label>
                                <span class="orderhelp" id="description_error" style="color: red;"></span>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button id="submit_help" class="btn btn-warning mx-auto">Send Query</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{--Model open on product review anchor trigger--}}
        <div class="modal fade" id="product_review_model" tabindex="-1" aria-labelledby="product_review_model" aria-hidden="true">
            <div class="modal-dialog modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Review the Product</h1>
                        <button type="button" data-bs-dismiss="modal"><i class="fa-solid fa-xmark fa-bounce fa-xl text-primary"></i></button>
                    </div>
                    <form autocomplete="off" id="review_form">
                        <div>
                            <div class="stars mt-4" id="star-rating">
                                <i class="fas fa-star" data-value="1"></i>
                                <i class="fas fa-star" data-value="2"></i>
                                <i class="fas fa-star" data-value="3"></i>
                                <i class="fas fa-star" data-value="4"></i>
                                <i class="fas fa-star" data-value="5"></i>
                            </div>
                            <input type="hidden" name="rating" id="rating" value="">
                        </div>

                        <div class="mt-4 mb-4" style="display: flex; justify-content: center; align-items: center;">
                            <textarea name="review" id="review" rows="4" cols="40" rows="4" placeholder="Enter your review here.........................."></textarea>
                        </div>
                        <div class="mt-2 mb-4" style="display: flex; justify-content: center; align-items: center;">
                            <button class="btn btn-warning mx-auto" id="review_submit" style="padding: 10px 20px; font-size: 16px;">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    {{--Footer--}}
    <x-footer />

    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{url('js/spinner.js')}}"></script> {{--Show Spinner during page loading--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#submit_help').click(function(e) {
                e.preventDefault();
                $('#submit_help').prop('disabled', true); // Disable the button
                $('.spinner-container').show(); //show spinner

                var bookingid = $('#booking_id').text();
                var subject = $('#subject').val();
                var description = $('#description').val();
                $.ajax({
                    url: '{{ route("myorder_detail_help") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        booking_id: bookingid,
                        subject: subject,
                        description: description,
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Clear all form fields
                            $('#need_help').get(0).reset(); // Using .get(0) to reset the form
                            $('#product_need_help_model').modal('hide');
                            toastr.success(response.message, "Success", {
                                "toastClass": "toastr-success"
                            });
                        } else if (response.status === 'error') {
                            toastr.error(response.message, "Error", {
                                "toastClass": "toastr-error"
                            });
                        } else if (response.status === 'validateerror') {
                            $('.orderhelp').text('');
                            var errors = response.message;
                            $.each(errors, function(key, value) {
                                $('#' + key + '_error').text(value[0]);
                            });
                        }

                    },
                    error: function() {
                        toastr.error('Something went wrong. Please try again.', "Error", {
                            "toastClass": "toastr-error"
                        });
                    },
                    complete: function() {
                        // Re-enable the button and hide the spinner after the request completes
                        $('#submit_help').prop('disabled', false);
                        $('.spinner-container').hide();
                    }
                });
            });


            //Js Code for star
            $('.stars i').on('click', function() {
                var rating = $(this).data('value');
                $('#rating').val(rating);

                // Update the stars
                $('.stars i').removeClass('active');
                for (var i = 1; i <= rating; i++) {
                    $('.stars i:nth-child(' + i + ')').addClass('active');
                }
            });

            //for fetch product_id for specific click(by anchor) on that product and store inside model and after form submit remove value from model
            $('a[data-bs-target="#product_review_model"]').click(function() {
                // Get the product ID from the clicked anchor tag
                var productId = $(this).data('product-id');
                // Store the product ID inside the modal using .data() 
                $('#product_review_model').data('product-id', productId);
            });


            // Review Submit
            $('#review_submit').click(function(e) {
                e.preventDefault();
                $('#review_submit').prop('disabled', true); // Disable the button
                $('.spinner-container').show(); //show spinner

                // Retrieve the product ID stored in the modal
                var product_id = $('#product_review_model').data('product-id');
                var booking_id = $('#booking_id').text();
                var rating = $('#rating').val();
                var review = $('#review').val();
                $.ajax({
                    url: '{{ route("review") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        product_id: product_id,
                        booking_id: booking_id,
                        review: review,
                        rating: rating,
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#product_review_model').modal('hide');
                            toastr.success(response.message, "Success", {
                                "toastClass": "toastr-success"
                            });
                            // Now remove the value of product_id from review model
                            $('#product_review_model').removeData('product-id')
                            $('#rating').val(''); //remove rating value i.e 123
                            $('.stars i').removeClass('active'); //remove star css which used inside js star
                            $('#review').val(''); //remove review field
                            //$('#review_form').get(0).reset(); // Using .get(0) to reset the form
                        } else if (response.status === 'error') {
                            toastr.error(response.message, "Error", {
                                "toastClass": "toastr-error"
                            });
                        } else if (response.status === 'validateerror') {
                            toastr.error('Something went wrong. Please check the review and try again.', "Error", {
                                "toastClass": "toastr-error"
                            });
                        }

                    },
                    error: function() {
                        toastr.error('Something went wrong. Please try again.', "Error", {
                            "toastClass": "toastr-error"
                        });
                    },
                    complete: function() {
                        // Re-enable the button and hide the spinner after the request completes
                        $('#review_submit').prop('disabled', false);
                        $('.spinner-container').hide();
                    }
                });
            });


            // Cancel order before delivery
            $('#cancel_order_click').click(function(e) {
                e.preventDefault();
                var bookingId = $('#booking_id').text();
                // Prompt the user for confirmation using SweetAlert
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to cancel this complete order. This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, cancel it!',
                    cancelButtonText: 'No, keep it',
                    customClass: {
                        //confirmButton: 'confirm-btn-sweet-class', //for change the color of confirm button by css class
                        confirmButton: 'btn btn-warning',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    // If the user clicks "Yes, cancel it!", proceed with the cancellation
                    if (result.isConfirmed) {
                        $('.spinner-container').show(); //show spinner
                        $.ajax({
                            url: '{{ route("myorder_cancel_order", ["id" => ":id"]) }}'.replace(':id', bookingId),
                            type: 'GET',
                            success: function(response) {
                                if (response.status === 'success') {
                                    // Show success message if cancellation is successful
                                    toastr.success(response.message, "Success", {
                                        "toastClass": "toastr-success"
                                    });
                                } else if (response.status === 'error') {
                                    // Show error message if there's an issue
                                    toastr.error(response.message, "Error", {
                                        "toastClass": "toastr-error"
                                    });
                                }
                            },
                            complete: function() {
                                $('.spinner-container').hide(); // Hide spinner after AJAX call completes
                            }
                        });
                    }
                });
            });



            // Return order after delivery
            $('#return_order_click').click(function(e) {
                e.preventDefault();
                var bookingId = $('#booking_id').text();
                // Prompt the user for confirmation using SweetAlert
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to return this complete order. This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, return it!',
                    cancelButtonText: 'No, keep it',
                    customClass: {
                        //confirmButton: 'confirm-btn-sweet-class', //for change the color of confirm button by css class
                        confirmButton: 'btn btn-warning',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    // Prompt the user for confirmation
                    // var isConfirmed = window.confirm("Are You Sure to Return this Complete Order?");
                    if (result.isConfirmed) {
                        $('.spinner-container').show(); //show spinner
                        $.ajax({
                            url: '{{ route("myorder_return_order", ["id" => ":id"]) }}'.replace(':id', bookingId),
                            type: 'GET',
                            success: function(response) {
                                if (response.status === 'success') {
                                    // Show success message if the return is successful
                                    toastr.success(response.message, "Success", {
                                        "toastClass": "toastr-success"
                                    });
                                } else if (response.status === 'error') {
                                    // Show error message if there's an issue
                                    toastr.error(response.message, "Error", {
                                        "toastClass": "toastr-error"
                                    });
                                }
                            },
                            complete: function() {
                                $('.spinner-container').hide(); // Hide spinner after AJAX call completes
                            }
                        });
                    }
                });
            });


            $('#invoice_download_on_click').click(function(e) {
                e.preventDefault();
                // Show the spinner when its processed
                $('.spinner-container').show();
                var bookingId = $('#booking_id').text();
                $.ajax({
                    url: '{{ route("user_invoice", ["id" => ":id"]) }}'.replace(':id', bookingId),
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob', // Treat the response as binary
                    },
                    success: function(response, status, xhr) {
                        // Extract filename from the Content-Disposition header
                        var filename = "Invoice_" + bookingId + ".pdf"; // Default filename
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('filename=') !== -1) {
                            var matches = disposition.match(/filename="([^"]+)"/);
                            if (matches.length === 2) {
                                filename = matches[1];
                            }
                        }
                        // Create a Blob from the response
                        var blob = new Blob([response], {
                            type: 'application/pdf'
                        });
                        // Create a link element for downloading the PDF
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename; // Set the filename to download
                        document.body.appendChild(link);
                        link.click(); // Trigger the download
                        document.body.removeChild(link); // Clean up
                        // Hide the spinner when completed
                        $('.spinner-container').hide();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error generating invoice:', error);
                        $('.spinner-container').hide(); //hide spinner when completed
                        alert('An error occurred while generating the invoice. Please try again.');
                    }
                });
            });

        });
    </script>
</body>

</html>