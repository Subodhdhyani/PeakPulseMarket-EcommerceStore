<x-dashboard_common>
    <x-slot:dynamic_title_top>
       Delivered Booking - PeakPulseMarket
        </x-slot>
        <x-slot:section_dynamic_content>
            <div class="container">
                {{--Heading--}}
                <h1 class="text-center text-danger">Manage Successfullly Delivered Booking</h1>
                {{--Table to show Booking Record--}}
                <div>
                    <table class="table  table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="d-none d-sm-table-cell">Id</th>
                                <th scope="col">Booking Id</th>
                                <th scope="col" class="d-none d-sm-table-cell">Name</th>
                                <th scope="col" class="d-none d-sm-table-cell">Email</th>
                                <th scope="col" class="d-none d-sm-table-cell">Order Status</th>
                                <th scope="col">Track</th>
                            </tr>
                        </thead>
                        <tbody id="fetch_booking">
                            <!-- Table body content comes here by ajax -->
                        </tbody>
                    </table>
                </div>
            </div>

            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script>
                $(document).ready(function() {
                    function loaddata() {
                        $('.spinner-container').show();
                        $.ajax({
                            url: "{{route('delivered_fetch_display')}}",
                            type: 'GET',
                            success: function(response) {
                                var respo = response.data;
                                $('#fetch_booking').empty();
                                if (respo.length === 0) {
                                    $('#fetch_booking').append('<tr><td colspan="6" class ="text-center text-danger" style="font-size: 30px;">No Delivered Booking/Order found</td></tr>');
                                } else {
                                    respo.forEach(function(record) {
                                        var trackUrl = '/track_shipped_booking/' + record.booking_id;
                                        $('#fetch_booking').append(
                                            '<tr>' +
                                            '<td class="d-none d-sm-table-cell">' + record.id + '</td>' +
                                            '<td><a href="/admin/booking_details_common/' + record.booking_id + '">' + record.booking_id + '</a></td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.billing_name + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.billing_email + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.order_status + '</td>' +
                                            '<td>' +
                                            '<a href="' + trackUrl + '" class="text-dark" title="Track Booking"><i class="fa-solid fa-location-dot"></i></a>' +
                                            '</td>' +
                                            '</tr>'
                                        )
                                    });

                                }
                            },
                            complete: function() {
                                $('.spinner-container').hide(); // Hide spinner after data is loaded
                            },
                            error: function() {
                                $('.spinner-container').hide(); // Hide spinner in case of error
                            }
                        })
                    }
                    loaddata();
                });
            </script>


            </x-slot>
</x-dashboard_common>