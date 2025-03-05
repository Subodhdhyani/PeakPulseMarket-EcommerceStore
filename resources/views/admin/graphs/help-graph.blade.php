<x-dashboard_common>
    <x-slot:dynamic_title_top>
        User Complains Chart - PeakPulseMarket
        </x-slot>
        <x-slot:section_dynamic_content>
            <style>
                /* For Small Screens */
                @media screen and (max-width: 600px) {
                    #complainChart {
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
                <h2 class="text-danger mt-4 mb-4">User Complains/Help Chart</h2>

                <canvas id="complainChart" width="400" height="200"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('complainChart').getContext('2d');

                // Data passed from the controller
                const newhelp = "{{ $newhelp }}";
                const pendinghelp = "{{ $pendinghelp }}";
                const resolvedhelp = "{{ $resolvedhelp }}";

                const complainChart = new Chart(ctx, {
                    type: 'bar', // change the type (e.g., 'line', 'pie', etc.)
                    data: {
                        labels: ['New Complain', 'Pending Complain', 'Resolved Complain'],
                        datasets: [{
                            label: 'Total Complains Details',
                            data: [newhelp, pendinghelp, resolvedhelp],
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