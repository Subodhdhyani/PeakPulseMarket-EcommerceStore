<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyOrder - PeakPulseMarket</title>
    @include('include.favicon')
    @include('include.fontawesome')
    @include('include.bootstrap')
    @include('include.spinner')
    <style>
        body {
            background-color: whitesmoke;
        }

        .container-custom-height {
            max-height: 40vh;
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
        }

        .second_section .second_sub_section,
        .second_section h4,
        .second_section h5 {
            margin: 10px 10px 0 10px;
        }

        .dropdown_payment {
            text-align: end;
            margin-top: 20px;
            margin-right: 5%;
        }

        .dropdown_payment select {
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #007bff;
            cursor: pointer;
        }
    </style>
</head>

<body>
    {{-- Navbar --}}
    <x-navbar />
    <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}
        {{--When click on booking for detail and some error then this messagge display receive from backend--}}
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <h4 class="text-center text-success mt-4 mb-4">Recently Ordered</h4>

        {{-- Dropdown for filtering orders --}}
        <div class="dropdown_payment">
            <select id="orderFilter">
                <option value="0">Payment Failed</option>
                <option value="1" selected>Payment Successful</option>
            </select>
        </div>

        {{-- Container where dynamically loaded orders will be displayed --}}
        <div id="ordersContainer"></div>
    </div>
    {{-- Footer --}}
    <x-footer />

    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{url('js/spinner.js')}}"></script> {{--Show Spinner during page loading--}}
    <script>
        $(document).ready(function() {
            // Function to fetch and display orders based on selected status
            function fetchOrders(status) {
                $('.spinner-container').show(); //show spinner
                $.ajax({
                    url: '{{ route("fetch_myorder") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        status: status
                    }, // Send the selected status
                    success: function(response) {
                        var ordersContainer = $('#ordersContainer');
                        ordersContainer.empty(); // Clear previous orders
                        // Handle the case when no orders are found
                        if (response.status === 'success') {
                            var statusArray = ["Order Placed", "Preparing", "Dispatched", "Delivered", "Cancelling", "Refunded", "Returning"];
                            var currentDate = new Date();
                            var formattedDate = currentDate.getDate() + '/' + (currentDate.getMonth() + 1) + '/' + currentDate.getFullYear();
                            $.each(response.data, function(index, order) {
                                var orderStatusText = statusArray[order.order_status] || "Unknown"; // Default to "Unknown" if index is out of range
                                var orderHtml = `
                                    <a href="/myorder_detail/${order.booking_id}">
                                        <div class="container mt-2 mb-2 container-custom-height">
                                            <div class="row h-100">
                                               <div class="col-2 first_section">
                                                    <img src="{{ asset('storage/product_image/') }}/${order.product.product_image}" alt="image" class="img-fluid">
                                                </div>
                                                <div class="col-10 second_section">
                                                    <div class="second_sub_section">
                                                        {{-- <h2>${order.product.product_name}</h2> --}} 
                                                        <h2>${order.product.product_name.substring(0, 5)}</h2>
                                                        <p class="text-primary d-none d-md-block">Category: ${order.product.category_name}</p>
                                                    </div>
                                                    <h4>&#8377;${order.total_amount_paid} <span>(${order.total_order_quantity} items)</span></h4>
                                                    <h5 class="text-primary d-none d-md-block">Status on ${formattedDate}: <strong class="text-danger">${orderStatusText}</strong></h5>
                                                    <h5 class="text-primary">Payment Status: <strong class="text-danger">${order.payment_status}</strong></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                 `;
                                ordersContainer.append(orderHtml);
                            });
                        } else if (response.status === 'error') {
                            ordersContainer.append('<h5 class="text-danger text-center">No orders found!</h5>');

                        }
                    },
                    error: function() {
                        $('#ordersContainer').append('<h5 class="text-danger text-center">Something went wrong. Please try again later.</h5>');
                    },
                    complete: function() {
                        $('.spinner-container').hide(); // Hide spinner after AJAX  as it run for both error and success
                    }
                });
            }
            // Initially load all successful payment orders
            fetchOrders(1); // Default to "payment success" orders when the page loads
            // When the dropdown value changes
            $('#orderFilter').change(function() {
                var status = $(this).val(); // Get the selected status value
                fetchOrders(status);
            });
        });
    </script>
</body>

</html>