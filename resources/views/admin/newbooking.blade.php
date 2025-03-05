<x-dashboard_common>
    <x-slot:dynamic_title_top>
       New Booking - PeakPulseMarket
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
                <h1 class="text-center text-danger">Manage/New Booking</h1>
                {{--Table to show Booking Record--}}
                <div>
                <div class="table-responsive">
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
            </div>

            <iframe id="printIframe" style="display:none;"></iframe>

            {{--Add jquery and Sweet Alert and toastr--}}
            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                $(document).ready(function() {
                    function loaddata() {
                        $('.spinner-container').show(); //show spinner
                        $.ajax({
                            url: "{{route('newbookingdisplay')}}",
                            type: 'GET',
                            success: function(response) {
                                var respo = response.data;
                                $('#fetch_booking').empty();
                                if (respo.length === 0) {
                                    $('#fetch_booking').append('<tr><td colspan="6" class ="text-center text-danger" style="font-size: 30px;">No New Booking/Order found</td></tr>');
                                    $('.spinner-container').hide(); //hide the spinner
                                } else {
                                    respo.forEach(function(record) {
                                        $('#fetch_booking').append(
                                            '<tr>' +
                                            '<td class="d-none d-sm-table-cell">' + record.id + '</td>' +
                                            '<td><a href="/admin/new_booking_detail/' + record.booking_id + '">' + record.booking_id + '</a></td>' +
                                            //'<td>' + record.booking_id + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.billing_name + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.billing_email + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.order_status + '</td>' +
                                            '<td>' +
                                            //'<a href="/admin/invoice_print/' + record.booking_id + '" class="text-dark" data-bs-toggle="tooltip" title="Print Invoice"><i class="fa-solid fa-print"></i></a>&emsp;' +
                                            //'<a href="' + "{{ route('invoice_print', ':booking_id') }}".replace(':booking_id', record.booking_id) + '" class="text-dark" data-bs-toggle="tooltip" title="Print Invoice"><i class="fa-solid fa-print"></i></a>&emsp;'+
                                            '<a href="javascript:void(0)" onclick="printInvoice(\'' + record.booking_id + '\')" class="text-dark" data-bs-toggle="tooltip" title="Print Invoice"><i class="fa-solid fa-print"></i></a>&emsp;' +
                                            '<a href="" class="new_booking_action text-dark" data-id="' + record.booking_id + '" data-action="accept" data-bs-toggle="tooltip" title="Accept Booking"><i class="bi bi-cart-check-fill"></i></a>&emsp;' +
                                            '<a href="" class="new_booking_action text-dark" data-id="' + record.booking_id + '" data-action="reject" data-bs-toggle="tooltip" title="Reject Booking"><i class="bi bi-cart-x-fill"></i></a>' +
                                            '</td>' +
                                            '</tr>'
                                        )
                                    });
                                    $('.spinner-container').hide(); //hide the spinner
                                    //Booking Accept or reject
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
                                                    loaddata()
                                                } else if (response.status === 'reject') {
                                                    toastr.error(response.message, "", {
                                                        "toastClass": "toastr-error"
                                                    });
                                                    $('.spinner-container').hide(); //hide the spinner
                                                    loaddata()
                                                }



                                            }
                                        });

                                    });



                                }
                            }
                        })
                    }
                    loaddata();

                    window.printInvoice = function(booking_id) {
                        var iframe = document.getElementById('printIframe');

                        // Ensure the iframe is available before trying to set src
                        if (iframe) {
                            $('.spinner-container').show(); //show spinner
                            iframe.src = "/admin/invoice_print/" + booking_id; // Load the invoice page in the iframe

                            iframe.onload = function() {
                                var iframeDocument = iframe.contentWindow.document;

                                // Hide the action buttons inside the iframe content
                                var actionButtons = iframeDocument.querySelector('.action-buttons');
                                if (actionButtons) {
                                    actionButtons.style.display = 'none'; // Hide action buttons
                                }

                                // Trigger the print dialog
                                iframe.contentWindow.print();
                                $('.spinner-container').hide(); //hide the spinner
                            };
                        } else {
                            console.error("Iframe element not found.");
                        }
                    };

                });
            </script>


            </x-slot>
</x-dashboard_common>