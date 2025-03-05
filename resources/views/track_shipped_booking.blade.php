<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Details - PeakPulseMarket</title>
    @include('include.favicon')
    @include('include.bootstrap')
    @include('include.fontawesome')
    @include('include.spinner')
    <style>
        .timeline {
            position: relative;
            margin-top: 50px;
            margin-bottom: 50px;
            padding-left: 40px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-left: 40px;
        }

        /* Circle Maker */
        .timeline-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -10px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #e0e0e0;
            border: 2px solid #fff;
            z-index: 2;
        }

        /* Green Circle for Completed Milestones */
        .timeline-item.complete::before {
            background-color: #4caf50;
        }

        .timeline-item.complete::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            width: 2px;
            height: 100%;
            background-color: #4caf50;
            z-index: 1;
        }

        .timeline-item:last-child.complete::after {
            height: 0;
        }

        /* In-Progress Circle */
        .timeline-item.in-progress::before {
            background-color: #ffc107;
        }

        .box-top-bottom {
            width: 100%;
            background-color: #ffc107;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
        }
    </style>
</head>

<body>
    <div class="box-top-bottom">
        <h4 style="font-family: 'Dancing Script', cursive; font-weight: bold;">Peak Pulse Market</h4>
    </div>
    <div class="container mt-5">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="text-center text-dark mb-4" style="margin: 0;">Booking Tracking Details</h1>
            <button onclick="window.history.back()" class="btn btn-secondary" style="border-radius: 0; padding: 10px 20px; height: auto;">Back</button>
        </div>
        <!-- Booking Details -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Booking Details</h5>
            </div>
            <div class="card-body">
                <p><strong>Booking ID:</strong> {{ $tracking->booking_id }}</p>
                <p><strong>Courier Name:</strong> {{ $tracking->courier_name }}</p>
                <p><strong>Courier Tracking ID:</strong> {{ $tracking->courier_tracking_number }}</p>
            </div>
        </div>


        <div class="timeline">
            <!-- Display milestones except after "Delivered" -->
            @foreach ($filteredTimeline as $status => $timestamp)
            @if (!in_array($status, ['Order Delivered', 'Order Delivered Now Returning By Customer', 'Order Return Received Refunding Soon', 'Order Refunded Successfully']))
            <div class="timeline-item complete">
                <h5>{{ $status }}</h5>
                <small>{{ \Carbon\Carbon::parse($timestamp)->format('d-m-Y h:i A') }}</small>
                <p>The "{{ $status }}" was completed.</p>
            </div>
            @endif
            @endforeach

            <!-- Check and display "Delivered" milestone -->
            @if (isset($filteredTimeline['Order Delivered']) && $filteredTimeline['Order Delivered'] !== null)
            <!-- Show Delivered as complete -->
            <div class="timeline-item complete">
                <h5>Delivered</h5>
                <small>{{ \Carbon\Carbon::parse($filteredTimeline['Order Delivered'])->format('d-m-Y h:i A') }}</small>
                <p>The "Order was successfully Delivered" to the customer.</p>
            </div>
            @else
            <!-- Show Delivered as in progress -->
            <div class="timeline-item in-progress">
                <h5>Delivered</h5>
                <small>In Progress</small>
                <p>The order is on its way to the customer and will be delivered soon.</p>
            </div>
            @endif
            <!--Order Delivered now returning by customer so also fetch return tracking detail-->
            @if (isset($filteredTimeline['Order Delivered Now Returning By Customer']) && $filteredTimeline['Order Delivered Now Returning By Customer'] !== null)
            <div class="timeline-item complete">
                <h5>Order Delivered Now Returning By Customer</h5>
                <small>{{ \Carbon\Carbon::parse($filteredTimeline['Order Delivered Now Returning By Customer'])->format('d-m-Y h:i A') }}</small>
                <p>The "{{$returnDetails}}" by Admin</p>
                @if($returnTracking && $returnTracking->courier_name != null && $returnTracking->courier_tracking_number != null)
                <div class="card mb-2 mt-2">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Return Tracking Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Return Status:</strong> {{ $returnDetails }}</p>
                        <p><strong>Courier Name:</strong> {{ $returnTracking->courier_name }}</p>
                        <p><strong>Courier Tracking ID:</strong> {{ $returnTracking->courier_tracking_number }}</p>
                    </div>
                </div>
                @endif
            </div>
            @endif
            <!-- Display milestones if any there, after delivered -->
            @foreach ($filteredTimeline as $status => $timestamp)
            @if (!in_array($status, ['Order Delivered','Order Placed', 'Order Cancelled By Customer Before Preparing', 'Order Preparing', 'Order Cancelled By Customer After Preparing', 'Order Dispatched','Order Delivered Now Returning By Customer']))
            <div class="timeline-item complete">
                <h5>{{ $status }}</h5>
                <small>{{ \Carbon\Carbon::parse($timestamp)->format('d-m-Y h:i A') }}</small>
                <p>The "{{ $status }}" was completed.</p>
            </div>
            @endif
            @endforeach

        </div>
    </div>
    <div class="box-top-bottom">
        <p><strong>© Jan 2025-{{ date("Y") }} Peak Pulse Market. All rights reserved.</strong></p>
    </div>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{url('js/spinner.js')}}"></script> {{--Show Spinner during page loading--}}
</body>

</html>