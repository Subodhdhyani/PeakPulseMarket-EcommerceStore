<x-dashboard_common>
    <x-slot:dynamic_title_top>
        Preparing Booking - PeakPulseMarket
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
                <h1 class="text-center text-danger">Manage Preparing Booking</h1>
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
                                <th scope="col">Operation</th>
                            </tr>
                        </thead>
                        <tbody id="fetch_booking">
                            <!-- Table body content comes here by ajax -->
                        </tbody>
                    </table>
                </div>
            </div>


            {{--Modal Add Tracking Details and Then Dispatched--}}
            <div class="modal fade" id="targetmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:antiquewhite">
                        <div class="modal-header  text-center">
                            <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel">Add Tracking Details</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form autocomplete="off" id="myform">
                                <div class="mb-3">
                                    <label for="courier_name" class="col-form-label">Courier Name</label>
                                    <input type="text" class="form-control" id="courier_name" name="courier_name" value="{{old('courier_name')}}" required>
                                    <span class="add_courier_error" id="courier_name_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="courier_trackid" class="col-form-label">Courier Tracking Id</label>
                                    <input type="text" class="form-control" id="courier_trackid" name="courier_trackid" value="{{old('courier_trackid')}}" required>
                                    <span class="add_courier_error" id="courier_trackid_error" style="color: red;"></span>
                                </div>
                                <hr>
                                <button type="button" id="add_track_details" class="btn btn-warning d-block mx-auto">Add Tracking Details and Dispatched</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            {{--Add jquery  and toastr--}}
            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                $(document).ready(function() {
                    function loaddata() {
                        $('.spinner-container').show();
                        $.ajax({
                            url: "{{route('preparing_fetch_display')}}",
                            type: 'GET',
                            success: function(response) {
                                var respo = response.data;
                                $('#fetch_booking').empty();
                                if (respo.length === 0) {
                                    $('#fetch_booking').append('<tr><td colspan="6" class ="text-center text-danger" style="font-size: 30px;">No Preparing Order found</td></tr>');
                                } else {
                                    respo.forEach(function(record) {
                                        $('#fetch_booking').append(
                                            '<tr>' +
                                            '<td class="d-none d-sm-table-cell">' + record.id + '</td>' +
                                            '<td><a href="/admin/preparing_detail/' + record.booking_id + '">' + record.booking_id + '</a></td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.billing_name + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.billing_email + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.order_status + '</td>' +
                                            '<td>' +
                                            '<a href="javascript:void(0);" class="preparing_booking_action text-dark" data-bs-toggle="modal" data-bs-target="#targetmodal" data-id="' + record.booking_id + '" data-bs-toggle="tooltip" title="Add Tracking/Dispatched"><i class="bi bi-truck"></i></a>' +
                                            '</td>' +
                                            '</tr>'
                                        )
                                    });

                                    //After Prepared Booking Now Shipped and set status 2   old use this 
                                    /*$('.preparing_booking_action').click(function(e) {
                                        e.preventDefault();
                                        var bookingId = $(this).data('id');
                                        $.ajax({
                                              url: " {   {   route('prepared_booking_shipped') }      }",
                                              type: 'POST',
                                              data: {
                                                  bookingId: bookingId,
                                              },
                                              headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                },
                                              success: function(response) {
                                                if(response.status ==='success'){
                                                        toastr.success(response.message, "", {
                                                        "toastClass": "toastr-success"
                                                                                             });
                                                        loaddata();
                                                    }
                                                       
                                                  
                                              }
                                            });

                                    });

                                    */
                                    //now add tracking details when product shipped
                                    //From anchor tag when open model for tracking details so from anchor tag pass booking_id to model submit button
                                    $(document).on('click', '.preparing_booking_action', function(e) {
                                        e.preventDefault();
                                        var bookingId = $(this).data('id'); // Get booking_id
                                        $('#add_track_details').data('id', bookingId); // Set booking_id on modal button
                                    });

                                    $('#add_track_details').click(function(e) {
                                        e.preventDefault();
                                        $('#add_track_details').prop('disabled', true); // Disable the button
                                        $('.spinner-container').show(); //show spinner
                                        var bookingId = $(this).data("id");
                                        var courier_name = $('#courier_name').val();
                                        var courier_trackid = $('#courier_trackid').val();
                                        $.ajax({
                                            url: "{{route('prepared_detail_add_track')}}",
                                            type: 'POST',
                                            data: {
                                                bookingId: bookingId,
                                                courier_name: courier_name,
                                                courier_trackid: courier_trackid
                                            },
                                            headers: { //for csf token also add meta tag at head tag
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            success: function(response) {
                                                if (response.status === 'success') {
                                                    $('#targetmodal').modal('hide');
                                                    $('#myform').trigger('reset');
                                                    loaddata();
                                                    toastr.success(response.message, "", {
                                                        "toastClass": "toastr-success"
                                                    });
                                                    $('#add_track_details').prop('disabled', false); //Re-enable the button
                                                    $('.spinner-container').hide(); //hide the spinner

                                                } else if (response.status === 'error') {
                                                    toastr.error(response.message, "", {
                                                        "toastClass": "toastr-error"
                                                    });
                                                    $('#add_track_details').prop('disabled', false); //Re-enable the button
                                                    $('.spinner-container').hide(); //hide the spinner
                                                } else if (response.status === 'validateerror') {
                                                    $('.add_courier_error').text(''); // Clear any previous error messages
                                                    var errors = response.message;
                                                    $.each(errors, function(key, value) {
                                                        $('#' + key + '_error').text(value[0]); // Update error message next to the field  //0 optional if miltille error inside same field then 0,1 etc used else for single error inside same field not required
                                                    });
                                                    $('#add_track_details').prop('disabled', false); //Re-enable the button
                                                    $('.spinner-container').hide(); //hide the spinner
                                                }

                                            },
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