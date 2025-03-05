<x-dashboard_common>

    <x-slot:dynamic_title_top>
        Complains - PeakPulseMarket
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
            <h1 class="text-center text-danger">Track Complains</h1>

            <table class="table  table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="d-none d-sm-table-cell">S.No</th>
                        <th scope="col" class="d-none d-sm-table-cell">Name</th>
                        <th scope="col" class="d-none d-sm-table-cell">Phone</th>
                        <th scope="col">BookingId</th>{{--Hide the Column on Small Screen--}}
                        <th scope="col" class="d-none d-sm-table-cell">Subject</th>
                        <th scope="col" class="d-none d-sm-table-cell">Description</th>{{--Hide the Column on Small Screen--}}
                        <th scope="col" colspan="2" class="text-center">Status <span class="d-none d-sm-inline">[Mark Check]</span></th>

                    </tr>
                </thead>
                <tbody id="table-body">
                    {{--
         Here Table body but in here we fetch dynamically from db
         --}}
                </tbody>
            </table>

            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                $(document).ready(function() {
                    function fetchComplains() {
                        $('.spinner-container').show();
                        $.ajax({
                            url: '{{ route("complainsget") }}',
                            type: 'GET',
                            success: function(data) {
                                // Clear existing table rows
                                $('#table-body').empty();
                                if (data.rec.length === 0) {
                                    $('#table-body').append('<tr><td colspan="7" class ="text-center text-danger" style="font-size: 30px;">No Complains found</td></tr>');
                                } else {
                                    //now fetch and attach to tbody by id selector
                                    data.rec.forEach(function(record) {
                                        var checkboxHtml = '<input type="checkbox" name="inprogress" class="inprogress-checkbox" data-id="' + record.id + '" ' + (record.order_help_status == 1 || record.order_help_status == 2 ? 'checked' : '') + '>';
                                        var checkboxHtml2 = '<input type="checkbox" name="resolved" class="resolved-checkbox" data-id="' + record.id + '" ' + (record.order_help_status == 2 ? 'checked' : '') + '>';
                                        var bookingIdLink = '<a href="/admin/complainsdetail/' + record.booking_id + '">' + record.booking_id + '</a>';
                                        var row = '<tr>' +
                                            '<td class="d-none d-sm-table-cell">' + record.id + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.tblbooking.billing_name + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.tblbooking.billing_phone + '</td>' +
                                            '<td>' + bookingIdLink + '</td>' + // This makes booking_id a link
                                            '<td class="d-none d-sm-table-cell">' + record.subject + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.description + '</td>' +
                                            '<td>' + checkboxHtml + '<p class="text-danger d-none d-sm-inline" style="display: inline;">(&check;:Inprogress)</p></td>' +
                                            '<td>' + checkboxHtml2 + '<p class="text-danger d-none d-sm-inline" style="display: inline;">(&check;:Resolved)</p></td>' +
                                            '</tr>';
                                        $('#table-body').append(row);
                                    });

                                }
                            },
                            complete: function() {
                                $('.spinner-container').hide(); // Hide spinner after data is loaded
                            },
                            error: function() {
                                $('.spinner-container').hide(); // Hide spinner in case of error
                            }

                        });
                    }

                    fetchComplains();
                    //setInterval(fetchComplains, 9000);

                });

                $(document).on('change', '.inprogress-checkbox, .resolved-checkbox', function() {
                    $('.spinner-container').show(); //show spinner
                    var checkbox = $(this);
                    var recordId = checkbox.data('id'); //get record's ID from data attribute
                    var status = 0; // Default status is 0
                    // Check which checkbox was clicked and set the status accordingly
                    if (checkbox.hasClass('inprogress-checkbox') && checkbox.is(':checked')) {
                        status = 1; // Set to 1 if the Inprogress checkbox is checked
                    } else if (checkbox.hasClass('resolved-checkbox') && checkbox.is(':checked')) {
                        status = 2; // Set to 2 if the Resolved checkbox is checked
                    } else {
                        // If the checkbox is unchecked, we do nothing (no update is sent to backend)
                        return;
                    }

                    // Send the updated status to the server
                    $.ajax({
                        url: "{{route('complainsstatusupdate')}}",
                        type: 'POST',
                        data: {
                            status: status,
                            recordId: recordId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                toastr.success(response.message, "", {
                                    "toastClass": "toastr-success"
                                });
                                $('.spinner-container').hide(); //hide the spinner
                            } else if (response.status === 'error') {
                                toastr.error(response.message, "", {
                                    "toastClass": "toastr-error"
                                });
                                $('.spinner-container').hide(); //hide the spinner
                            } else if (response.status === 'validateerror') {
                                // Loop through the validation errors object
                                $.each(response.message, function(field, messages) {
                                    // Loop through the messages array for each field
                                    $.each(messages, function(index, errorMessage) {
                                        toastr.error(errorMessage, "", {
                                            "toastClass": "toastr-error"
                                        });
                                    });
                                });
                            }
                        },
                        error: function() {
                            $('.spinner-container').hide();
                        }
                    });
                });
            </script>


            </x-slot>
</x-dashboard_common>