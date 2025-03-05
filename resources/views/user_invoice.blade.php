<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Invoice - PeakPulseMarket</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color:rgb(212, 215, 214);
        }
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .header-table td {
            border: none;
        }
        .details-table td {
            border: none;
            padding: 5px 0;
        }
        .secondary-details-table {
            width: 100%;
            margin-top: -10px;
            border: none;
        }
        .secondary-details-table td {
            border: none; 
            padding: 5px 0;
        }
        .items-table th, .items-table td {
            border: 1px solid #000; 
        }
        .payment-details-table th, .payment-details-table td {
            border: 1px solid #000; 
        }
        .signature {
            text-align: right;
            margin-top: 20px;
        }
        .signature img {
            width: 150px;
            height: 50px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <!-- Logo on the Left -->
            <td style="width: 50%; text-align: left;">
                <img src="{{ public_path('Business_Logo/logo1.png') }}" alt="Business Logo" style="width: 100px; height: 90px;">
            </td>
            <!-- Tax Invoice Text on the Right -->
            <td style="width: 50%; text-align: right;">
                <div style="font-size: 20px; font-weight: bold;">Tax Invoice/Bill of Supply</div>
            </td>
        </tr>
    </table>

    <!-- Customer Details -->
    <table class="details-table">
        <tr>
            <td style="width: 50%;">
                <strong>Sold by:</strong><br>
                Peak Pulse Market<br>
                Sahastradhara Rd, Dehradun, 248001, Uttarakhand, India<br>
                <strong>PAN No.:</strong> UKLPM7148L<br>
                <strong>GST Reg No.:</strong> 28UKLPM7148L1ZS
            </td>
            <td style="width: 50%;">
                <strong>Shipping/Billing Address:</strong><br>
                {{ $booking_detail->first()->billing_name }}<br>
                {{ $booking_detail->first()->billing_address }}<br>
                <strong>Phone No:</strong> {{ $booking_detail->first()->billing_phone }}
            </td>
        </tr>
    </table>

    <!-- Secondary Details: Booking Info -->
    <table class="secondary-details-table">
        <tr>
            <td style="width: 50%;">
                <strong>Booking Number:</strong> {{ $booking_detail->first()->booking_id }}<br>
                <strong>Order Date:</strong> {{ $booking_detail->first()->created_at->format('d-m-Y h:i A') }}<br>
                <strong>Invoice Date:</strong> {{ now()->format('d-m-Y h:i A') }}
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
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
                <td>₹ {{ $booking_detail->first()->delivery_charges }}</td>
            </tr>
            <tr>
                <td colspan="7"><strong>TOTAL</strong></td>
                <td><strong>&#8377; {{ $booking_detail->first()->total_amount_paid }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Signature Section -->
    <div class="signature">
        <img src="{{ public_path('Signature/Signature.jpg') }}" alt="Authorized Signature"><br>
        <strong>Authorized Signature</strong>
    </div>

    <!-- Payment Details -->
    <table class="payment-details-table" style="width: 100%; margin-top: 20px;">
        <tr>
            <td style="width: 33%;"><strong>Payment Transaction ID:</strong> {{ $booking_detail->first()->payment_id }}</td>
            <td style="width: 33%;"><strong>Date and Time:</strong> {{ $booking_detail->first()->created_at->format('d-m-Y h:i A') }}</td>
            <td style="width: 33%;"><strong>Mode of Payment:</strong> {{ $booking_detail->first()->payment_mode }}</td>
        </tr>
    </table>

</body>
</html>
