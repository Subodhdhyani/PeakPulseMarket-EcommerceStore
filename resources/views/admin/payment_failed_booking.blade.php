<x-dashboard_common>
    <x-slot:dynamic_title_top>
        Payment Failed Booking - PeakPulseMarket
        </x-slot>
        <x-slot:section_dynamic_content>
            <style>
                .toastr-success {
                    background-color: green !important;
                }

                .toastr-error {
                    background-color: red !important;
                }
            </style>
            <div class="container">
                {{--Heading--}}
                <h1 class="text-center text-danger">Payment Failed Booking</h1>
                {{--Table to show Failed Booking Record--}}
                <div>
                    <table class="table  table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="d-none d-sm-table-cell">Id</th>
                                <th scope="col">Booking Id</th>
                                <th scope="col" class="d-none d-sm-table-cell">Name</th>
                                <th scope="col" class="d-none d-sm-table-cell">Email</th>
                                <th scope="col" class="d-none d-sm-table-cell">Order Status</th>
                                <th scope="col">Operation</th>
                            </tr>
                        </thead>
                        <tbody id="fetch_booking">
                            <!-- Table body content comes here by ajax -->
                        </tbody>
                    </table>
                </div>
            </div>

            {{--Add jquery and Sweet Alert and toastr--}}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                $(document).ready(function() {
                    function loaddata() {
                        $('.spinner-container').show();
                        $.ajax({
                            url: "{{route('payment_failed_fetch')}}",
                            type: 'GET',
                            success: function(response) {
                                var respo = response.data;
                                $('#fetch_booking').empty();
                                if (respo.length === 0) {
                                    $('#fetch_booking').append('<tr><td colspan="6" class ="text-center text-danger" style="font-size: 30px;">No Failed Payment Booking/Order Found</td></tr>');
                                } else {
                                    respo.forEach(function(record) {
                                        $('#fetch_booking').append(
                                            '<tr>' +
                                            '<td class="d-none d-sm-table-cell">' + record.id + '</td>' +
                                            '<td><a href="/admin/payment_failed_detail/' + record.booking_id + '">' + record.booking_id + '</a></td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.billing_name + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.billing_email + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.order_status + '</td>' +
                                            '<td>' +
                                            '<a href="" class="payment_failed_booking_action text-dark" data-id="' + record.booking_id + '" data-bs-toggle="tooltip" title="Delete Payment Failed Booking"><i class="bi bi-archive-fill"></i></a>&emsp;' +

                                            '</td>' +
                                            '</tr>'
                                        )
                                    });

                                    //Delete Payment Failed  Booking
                                    $('.payment_failed_booking_action').click(function(e) {
                                        e.preventDefault();
                                        $('.spinner-container').show(); //show spinner
                                        var recordId = $(this).data('id');
                                        // SweetAlert confirmation
                                        swal({
                                            title: "Are you sure?",
                                            text: "To Delete this booking Details !",
                                            icon: "warning",
                                            buttons: true,
                                            dangerMode: true,
                                        }).then((willDelete) => {
                                            if (willDelete) {
                                                //deletion via AJAX
                                                $.ajax({
                                                    url: '/admin/payment_failed_delete/' + recordId,
                                                    type: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    },
                                                    success: function(response) {
                                                        if (response.status === 'success') {
                                                            toastr.success(response.message, "", {
                                                                "toastClass": "toastr-success"
                                                            });
                                                            $(this).closest('tr').hide();
                                                            $('.spinner-container').hide(); //hide the spinner
                                                        } else if (response.status === 'error') {
                                                            toastr.error(response.message, "", {
                                                                "toastClass": "toastr-error"
                                                            });
                                                            $('.spinner-container').hide(); //hide the spinner
                                                        }
                                                    }.bind(this) //remove bind when use other closet way
                                                });
                                            } else {
                                                swal("Cancelled", "Delete Booking Details !", "info");
                                                $('.spinner-container').hide(); //hide the spinner
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