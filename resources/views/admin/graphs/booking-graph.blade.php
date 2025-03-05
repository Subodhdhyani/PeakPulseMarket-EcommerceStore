<x-dashboard_common>
    <x-slot:dynamic_title_top>
        Booking Records Chart - PeakPulseMarket
        </x-slot>
        <x-slot:section_dynamic_content>
            <style>
                /* For Small Screens */
                @media screen and (max-width: 600px) {
                    #bookingChart {
                        height: 300px !important;
                    }
                }
            </style>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <span>
                <a href="javascript:void(0);"
                    style="text-decoration: none; font-family: 'Dancing Script', cursive; font-weight: bold; border: 2px solid currentColor; border-radius: 20px; padding: 5px 15px; display: inline-block;"
                    class="text-danger"
                    title="Go Back"
                    onclick="history.back()">
                    Go Back
                </a>
            </span>
            <div style="width: 80%; margin: auto; text-align: center; height: 400px; max-height: 90vh;">
                
                <h2 class="text-danger">Sale / Booking Records Chart</h2>
                <h6>Total Sales (Delivered/Non Returns) <strong> = {{ number_format($deliveredTotalAmount, 2) }}</strong></h6>
                @if ($mostSoldProduct)
                <h6>
                    <strong>Top Picks : </strong>Product ID:
                    <a href="{{ route('product_checkout', ['id' => $mostSoldProduct->tblproduct_id]) }}" target="_blank">
                        {{ $mostSoldProduct->tblproduct_id }}
                    </a>
                    has been sold<strong> {{ $mostSoldProduct->count }} </strong>times.
                </h6>
                @else
                <h6>No sales data available.</h6>
                @endif


                <canvas id="bookingChart"></canvas>

            </div>

            <script>
                const ctx = document.getElementById('bookingChart').getContext('2d');

                // Data passed from the controller
                const totalnewbooking = "{{ $totalnewbooking }}";
                const deliveredCount = "{{ $deliveredCount }}";
                const refundedCount = "{{ $refundedCount }}";
                const failedPaymentCount = "{{ $failedPaymentCount }}";

                const bookingChart = new Chart(ctx, {
                    type: 'bar', // change the type (e.g., 'line', 'pie', etc.)
                    data: {
                        labels: ['New Booking', 'Delivered Orders', 'Refunded Orders', 'Failed Payments'],
                        datasets: [{
                            label: 'Total Sale/Booking Details',
                            data: [totalnewbooking, deliveredCount, refundedCount, failedPaymentCount],
                            backgroundColor: [
                                'rgba(18, 223, 66, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 206, 86, 0.6)'
                            ],
                            borderColor: [
                                'rgba(18, 223, 66, 0.6)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 206, 86, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        maintainAspectRatio: false, // Allows chart to follow CSS height
                        responsive: true, // Enables responsiveness
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
            {{--Show Spinner during page loading--}}
            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="{{url('js/spinner.js')}}"></script>
            </x-slot>
</x-dashboard_common>