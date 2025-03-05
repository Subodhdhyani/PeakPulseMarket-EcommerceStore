<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Order Invoice - PeakPulseMarket</title>
    @include('include.favicon')
    @include('include.fontawesome')
    @include('include.bootstrap')
    @include('include.spinner')
    <style>
        p {
            margin: 0;
            /* Remove default margin */
        }

        .invoice-wrapper {
            width: 90%;
            padding: 20px 0;
            margin: 0 auto;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-header img {
            width: 100px;
            height: auto;
        }

        .invoice-title {
            font-size: 18px;
            font-weight: bold;
        }

        .invoice-content {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }

        .invoice-section {
            width: 48%;
        }

        .invoice-table-container {
            width: 100%;
            padding: 0;
            margin-top: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border: 1px solid #000;
        }

        th,
        td {
            border-right: 1px solid #000;
            border-left: 1px solid #000;
        }

        thead {
            border-bottom: 1px solid #000;
            background-color: darkgray;
        }

        tbody tr:nth-last-child(2),
        tr:last-child {
            border-top: 1px solid #000;
        }

        .signature {
            width: 30%;
            margin-left: auto;
            text-align: center;
        }

        .signature img {
            width: 150px;
            height: 50px;
        }

        .payment-details {
            width: 100%;
            margin-top: 20px;
            display: flex;
        }

        .payment-box {
            border: 1px solid #000;
            width: 34%;
            padding: 10px;
        }

        .action-buttons {
            margin-top: 20px;
        }

        .action-buttons button {
            margin-right: 10px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
   
    <div class="invoice-wrapper">

        <!-- Header: Logo and Title -->
        <div class="invoice-header">
            <img src="{{ asset('Business_Logo/logo1.png') }}" alt="Business Logo">
            <div class="invoice-title">Tax Invoice/Bill of Supply</div>
        </div>

        <!-- Content: Customer and Order Details -->
        <div class="invoice-content">
            <div class="invoice-section">
                <strong>Sold by:</strong>
                <p>Peak Pulse Market</p>
                <p>Sahastradhara Rd, Dehradun,248001,Uttarakhand, India</p>
                <h6 class="mt-2"><strong>PAN No.</strong>UKLPM7148L</h6>
                <h6><strong>GST Reg No.</strong>28UKLPM7148L1ZS</h6>
            </div>
            <div class="invoice-section">
                <strong>Shipping/Billing Address :</strong>
                <p class="mt-2">{{$booking_detail->first()->billing_name}}</p>
                <p>{{$booking_detail->first()->billing_address}}</p>
                <p><strong>Phone No: </strong>{{$booking_detail->first()->billing_phone}}</p>
            </div>
        </div>

        <div class="mt-4">
            <p><strong>Booking Number : </strong>{{$booking_detail->first()->booking_id}}</p>
            <p><strong>Order Date : </strong>{{$booking_detail->first()->created_at->format('d-m-Y H:i:s')}}</p>
            <p><strong>Invoice Date : </strong>{{ now()->format('d-m-Y H:i:s') }}</p>
        </div>

        <!-- Table: Invoice Items -->
        <div class="invoice-table-container">
            <table>
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Description</th>
                        <th>Original Price</th>
                        <th>Quantity</th>
                        <th>Tax Rate</th>
                        <th>Tax Type</th>
                        <th>Tax Amount</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking_detail as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detail->product->product_name }}</td>
                        <td>{{ number_format($detail->sale_prices - ($detail->sale_prices * 0.18), 2) }}</td>
                        <td>1</td>
                        <td>18%</td>
                        <td>GST</td>
                        <td>{{ number_format($detail->sale_prices * 0.18, 2) }}</td>
                        <td>{{ $detail->sale_prices }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="7">Shipping Charges</td>
                        <td>&#8377; {{$booking_detail->first()->delivery_charges}}</td>
                    </tr>
                    <tr>
                        <td colspan="7"><strong>TOTAL</strong></td>
                        <td><strong>&#8377; {{$booking_detail->first()->total_amount_paid}}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Signature-->
        <div class="signature">
            <img src="{{ asset('Signature/Signature.jpg') }}" alt="Authorized Signature">
            <h6>Authorized Signature</h6>
        </div>

        <!-- Payment Details -->
        <div class="payment-details">
            <div class="payment-box">
                <p>Payment Transaction ID: {{$booking_detail->first()->payment_id}}</p>
            </div>
            <div class="payment-box">
                <p>Date and Time: {{$booking_detail->first()->created_at->format('d-m-Y H:i:s')}}</p>
            </div>
            <div class="payment-box">
                <p>Mode of Payment: {{$booking_detail->first()->payment_mode}}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn btn-warning" onclick="printInvoice()">Print Invoice</button>
            <button class="btn btn-warning new_booking_action" data-id="{{ $booking_detail->first()->booking_id }}" data-action="accept">Mark as Preparing</button>
            <button class="btn btn-warning" onclick="history.back()">Back</button>
        </div>
    </div>

    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{url('js/spinner.js')}}"></script> {{--spinner when page load --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function printInvoice() {
            const buttons = document.querySelector('.action-buttons');
            buttons.style.display = 'none'; // Hide buttons
            window.print(); // Trigger print dialog
            setTimeout(() => {
                buttons.style.display = 'block'; // Show buttons again
            }, 500);
        }

        $(document).ready(function() {
            $('.new_booking_action').click(function(e) {
                e.preventDefault();
                $('.spinner-container').show(); //show spinner
                var bookingId = $(this).data('id');
                var action = $(this).data('action');

                $.ajax({
                    url: "{{ route('newbooking_action') }}",
                    type: 'POST',
                    data: {
                        bookingId: bookingId,
                        action: action
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'accept') {
                            toastr.success(response.message, "", {
                                "toastClass": "toastr-success"
                            });
                            $('.spinner-container').hide(); //hide the spinner
                        } else if (response.status === 'error') {
                            toastr.error(response.message, "", {
                                "toastClass": "toastr-error"
                            });
                            $('.spinner-container').hide(); //hide the spinner
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>