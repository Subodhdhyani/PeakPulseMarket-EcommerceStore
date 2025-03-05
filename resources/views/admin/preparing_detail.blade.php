<x-dashboard_common>
    <x-slot:dynamic_title_top>
        Preparing Booking Detail - PeakPulseMarket
        </x-slot>

        <x-slot:section_dynamic_content>
            <style>
                .container1 {
                    background-color: white;
                    max-height: 35vh;
                    height: auto;
                    border: 2px solid grey;
                    border-radius: 5px;
                }

                .hide_anchor_style {
                    text-decoration: none;
                    color: inherit;
                }

                .first_section {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                    max-height: 100%;
                    height: auto;
                }

                .first_section img {
                    max-width: 100%;
                    max-height: 100%;
                    width: auto;
                    height: auto;
                }

                @media (max-width: 900px) {
                    .first_section {
                        max-height: 100%;
                        height: auto;
                    }

                    .first_section img {
                        max-width: 100%;
                        max-height: 100%;
                        height: auto;
                        width: auto;
                        margin-left: 10px;
                    }
                }
            </style>
            @if($booking_detail->isNotEmpty())
            <div class="container1 mt-2 mb-2">
                <h6 class="text-center text-success">Booking id : <span id="booking_id">{{$booking_detail->first()->booking_id}}</span></h6>
                <h6 class="text-center text-success">This Booking id Contains total <span class="text-danger">{{$booking_detail->first()->total_order_quantity}} items.</span></h6>
                <div class="row h-100">
                    <div class="col-5">
                        <h5 class="d-none d-md-block">Delivery Address</h5>
                        <h6>{{$booking_detail->first()->billing_name}}</h6>
                        <h6>{{$booking_detail->first()->billing_address}}</h6>
                        <h6 class="d-none d-md-block"><strong>Phone :</strong>&ensp;{{$booking_detail->first()->billing_phone}}</h6>
                    </div>
                    <div class="col-3">
                        <h5 class="d-none d-md-block text-center">Total Amount & Items</h5>
                        <h6 class="text-center">&#8377; {{$booking_detail->first()->total_amount_paid}}<span class="text-danger"> ({{$booking_detail->first()->total_order_quantity}} items)</span></h6>
                    </div>
                    <div class="col-4">
                        <h6 class="text-primary text-center">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#targetmodal"
                                style="text-decoration: none; font-family: 'Dancing Script', cursive; font-weight: bold; border: 2px solid currentColor; border-radius: 20px; padding: 5px 10px; display: inline-block;">
                                Add Tracking / Dispatch</a>
                        </h6>
                        <h6 class="text-center mt-2">
                            <a href="javascript:history.back()"
                                style="text-decoration: none; font-family: 'Dancing Script', cursive; font-weight: bold; border: 2px solid currentColor; padding: 5px 15px; display: inline-block;"
                                class="text-dark"
                                title="Go Back">Back</a>
                        </h6>

                    </div>
                </div>
            </div>
            @endif
            @foreach($booking_detail as $booking_details)
            <div class="container1 mt-2 mb-2">
                <div class="row h-100">
                    <div class="col-2 first_section">
                        <a href="{{ route('product_checkout', ['id' => $booking_details->product->id]) }}" class="hide_anchor_style"><img src="{{ asset('storage/product_image/' . $booking_details->product->product_image) }}" alt="product_image" class="img-fluid"></a>
                    </div>
                    <div class="col-4 second_section">
                        <h5 class="mt-2"><a href="{{ route('product_checkout', ['id' => $booking_details->product->id]) }}" class="hide_anchor_style">{{ ucwords($booking_details->product->product_name)}}</a></h5>
                        <h6 class="text-primary d-none d-md-block">Category : {{ ucwords($booking_details->product->category_name) }}</h6>
                        <h6 class="text-primary">Seller : Peak Pulse Market</h6>
                        <h5>&#8377; {{$booking_details->sale_prices}} <span class="text-danger">(Product Id : {{$booking_details->product->id}})</span></h5>
                    </div>
                    <div class="col-6 third_section">
                        <h6 class="text-success mt-2">Status : <strong>{{$booking_details->order_status_text}} on {{$booking_details->updated_at->format('d-m-Y H:i:s')}}</strong></h6>
                    </div>
                </div>
            </div>
            @endforeach


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

            {{--Add jquery and toastr--}}
            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            {{--Show Spinner during page loading--}}
            <script src="{{url('js/spinner.js')}}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                $(document).ready(function() {

                    $('#add_track_details').click(function(e) {
                        e.preventDefault();
                        $('#add_track_details').prop('disabled', true); // Disable the button
                        $('.spinner-container').show(); //show spinner
                        var bookingId = $('#booking_id').text();
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


                });
            </script>
            </x-slot>

</x-dashboard_common>