<x-dashboard_common>
    <x-slot:dynamic_title_top>
        Products Details Chart - PeakPulseMarket
        </x-slot>
        <x-slot:section_dynamic_content>
            <style>
                /* For Small Screens */
                @media screen and (max-width: 600px) {
                    #productChart {
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
                <h2 class="text-danger mt-4 mb-4">Products Details Chart</h2>
                <canvas id="productChart" width="400" height="200"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('productChart').getContext('2d');

                // Data passed from the controller
                const totalpulse = "{{ $totalpulse }}";
                const totalspice = "{{ $totalspice }}";
                const totalsnack = "{{ $totalsnack }}";
                const totalpickle = "{{ $totalpickle }}";

                const outOfStockPulse = "{{ $outOfStockPulse }}";
                const outOfStockSpice = "{{ $outOfStockSpice }}";
                const outOfStockSnack = "{{ $outOfStockSnack }}";
                const outOfStockPickle = "{{ $outOfStockPickle }}";

                const productChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Pulses', 'Spices', 'Snacks', 'Pickles'],
                        datasets: [{
                                label: 'In Stock',
                                data: [totalpulse, totalspice, totalsnack, totalpickle],
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Out of Stock',
                                data: [outOfStockPulse, outOfStockSpice, outOfStockSnack, outOfStockPickle],
                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
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

            {{-- Show Spinner during page loading --}}
            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="{{ url('js/spinner.js') }}"></script>
            </x-slot>
</x-dashboard_common>