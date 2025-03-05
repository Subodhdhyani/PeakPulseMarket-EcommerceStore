<x-dashboard_common>
    <x-slot:dynamic_title_top>
      Cancelled Booking - PeakPulseMarket
        </x-slot>
        <x-slot:section_dynamic_content>
            <div class="container">
                {{--Heading--}}
                <h1 class="text-center text-danger">Manage Cancelled Booking</h1>
                {{--Table to show Booking Record--}}
                <div>
                    <table class="table  table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="d-none d-sm-table-cell">Id</th>
                                <th scope="col">BookingId</th>
                                <th scope="col" class="d-none d-sm-table-cell">Name</th>
                                <th scope="col" class="d-none d-sm-table-cell">Email</th>
                                <th scope="col" class="d-none d-sm-table-cell">Order Status</th>
                                <th scope="col">Initiate Refund</th>
                            </tr>
                        </thead>
                        <tbody id="fetch_booking">
                            <!-- Table body content comes here by ajax -->
                        </tbody>
                    </table>
                </div>
            </div>

            {{--Add jquery and Sweet Alert and toastr--}}
            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                $(document).ready(function() {
                    function loaddata() {
                        $('.spinner-container').show();
                        $.ajax({
                            url: "{{route('cancel_fetch_display')}}",
                            type: 'GET',
                            success: function(response) {
                                var respo = response.data;
                                $('#fetch_booking').empty();
                                if (respo.length === 0) {
                                    $('#fetch_booking').append('<tr><td colspan="6" class ="text-center text-danger" style="font-size: 30px;">No Cancelled Booking/Order found</td></tr>');
                                } else {
                                    respo.forEach(function(record) {
                                        $('#fetch_booking').append(
                                            '<tr>' +
                                            '<td class="d-none d-sm-table-cell">' + record.id + '</td>' +
                                            '<td><a href="/admin/booking_details_common/' + record.booking_id + '">' + record.booking_id + '</a></td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.billing_name + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.billing_email + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.order_status + '</td>' +
                                            '<td>' +
                                            '<a href="" class="cancel_booking_to_refund text-dark" data-id="' + record.booking_id + '" data-bs-toggle="tooltip" title="Reject/Refund"><i class="fa-brands fa-r-project"></i></a>' +
                                            '</td>' +
                                            '</tr>'
                                        )
                                    });


                                    //After Returned Booking Now refunded/rejected and set status 5
                                    $('.cancel_booking_to_refund').click(function(e) {
                                        e.preventDefault();
                                        //for diable anchor tag multiple times // Disable the anchor tag
                                        var $this = $(this); // Cache the clicked element
                                        if ($this.data('disabled')) {
                                            return; // Exit if already disabled
                                        }
                                        $this.data('disabled', true).css('pointer-events', 'none').css('opacity', '0.5');

                                        // Show the spinner when the refund is being processed this spinner class used in common dashboard here just show
                                        $('.spinner-container').show(); //show spinner
                                        var bookingId = $(this).data('id');
                                        $.ajax({
                                            url: "{{route('cancel_booking_to_refund')}}",
                                            type: 'Post',
                                            data: {
                                                bookingId: bookingId,
                                            },
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            success: function(response) {
                                                if (response.status === 'success') {
                                                    toastr.success(response.message, "", {
                                                        "toastClass": "toastr-success"
                                                    });
                                                    $this.data('disabled', false).css('pointer-events', 'auto').css('opacity', '1'); // Re-enable the anchor tag after the operation
                                                    $('.spinner-container').hide(); //hide the spinner
                                                    loaddata();
                                                } else if (response.status === 'error') {
                                                    toastr.error(response.message, "", {
                                                        "toastClass": "toastr-error"
                                                    });
                                                    $this.data('disabled', false).css('pointer-events', 'auto').css('opacity', '1'); // Re-enable the anchor tag after the operation
                                                    $('.spinner-container').hide(); //hide the spinner
                                                    loaddata();
                                                }

                                            }
                                        });

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