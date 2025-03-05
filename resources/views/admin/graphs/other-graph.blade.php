<x-dashboard_common>
    <x-slot:dynamic_title_top>
        Other Details Chart - PeakPulseMarket
        </x-slot>
        <x-slot:section_dynamic_content>
            <style>
                /* For Small Screens */
                @media screen and (max-width: 600px) {
                    #otherChart {
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
                <h2 class="text-danger mt-4 mb-4">Other Details Chart</h2>

                <canvas id="otherChart" width="400" height="200"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('otherChart').getContext('2d');

                // Data passed from the controller
                const totalcategory = "{{ $totalcategory }}";
                const total_pending_items_in_carts = "{{ $total_pending_items_in_carts}}";
                const totalreviewpending = "{{ $totalreviewpending }}";

                const otherChart = new Chart(ctx, {
                    type: 'bar', // change the type (e.g., 'line', 'pie', etc.)
                    data: {
                        labels: ['Total Category', 'Total Pending Cart Items ', 'Total Reviews Pending to Approval'],
                        datasets: [{
                            label: 'Total Order Return Request',
                            data: [totalcategory, total_pending_items_in_carts, totalreviewpending],
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.6)', // Light green for delivered
                                'rgba(255, 99, 132, 0.6)', // Light red for refunded
                                'rgba(255, 206, 86, 0.6)' // Light yellow for failed payments
                            ],
                            borderColor: [
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