<x-dashboard_common>

    <x-slot:dynamic_title_top>
        Booking Detail - PeakPulseMarket
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
                        <h6 class="text-primary text-center mt-2">
                            <a href="{{ route('invoice_print', ['id' => $booking_detail->first()->booking_id]) }}"
                                style="text-decoration: none; font-family: 'Dancing Script', cursive; font-weight: bold; border: 2px solid currentColor; border-radius: 20px; padding: 5px 10px; display: inline-block;">
                                Print Invoice</a>
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

            {{--Show Spinner during page loading--}}
            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="{{url('js/spinner.js')}}"></script>

            </x-slot>
</x-dashboard_common>