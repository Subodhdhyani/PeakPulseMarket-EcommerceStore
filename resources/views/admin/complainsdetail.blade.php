<x-dashboard_common>

    <x-slot:dynamic_title_top>
        Complains Detail - PeakPulseMarket
        </x-slot>

        <x-slot:section_dynamic_content>
            <style>
                .container1 {
                    background-color: white;
                    max-height: 35vh;
                    height: auto;
                    border: 2px solid grey;
                    border-radius: 5px;
                    max-width: 100%;
                    width: auto;
                }

                @media screen and (max-width: 576px) {
                    .container1 {
                        width: auto;
                    }

                }


                .container_complain {
                    background-color: white;
                    overflow-x: hidden;
                    border: 2px solid grey;
                    border-radius: 5px;
                    max-width: 100%;
                    width: auto;
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
                    height: 100%;
                }

                .first_section img {
                    max-width: 100%;
                    max-height: 100%;
                    width: auto;
                    height: auto;
                }

                p {
                    margin: 5px 0;
                    padding: 0;
                }
            </style>
            @if($complain_detail->isNotEmpty())
            <div class="container1 mt-2 mb-2">
                <h6 class="text-center text-success">Booking id : <span id="booking_id">{{$complain_detail->first()->booking_id}}</span></h6>
                <h6 class="text-center text-success">This Booking id Contains total <span class="text-danger">{{$complain_detail->first()->total_order_quantity}} items.</span></h6>
                <div class="row h-100">
                    <div class="col-8">
                        <h5 class="d-none d-md-block">Delivery Address</h5>
                        <h6>{{$complain_detail->first()->billing_name}}</h6>
                        <p>{{$complain_detail->first()->billing_address}}</p>
                        <h6 class="d-none d-sm-block"><strong>Phone :</strong>&ensp;{{$complain_detail->first()->billing_phone}}</h6>
                    </div>
                    <div class="col-4">
                        <h6 class="text-center text-danger mt-4">
                            <a href="{{ route('track_shipped_booking', ['id' => $complain_detail->first()->booking_id]) }}"
                                style="text-decoration: none; font-family: 'Dancing Script', cursive; font-weight: bold; border: 2px solid currentColor; border-radius: 20px; padding: 5px 15px; display: inline-block;"
                                class="text-primary"
                                title="Track Booking">Track</a>
                        </h6>
                        <h6 class="text-center mt-4">
                            <a href="javascript:history.back()"
                                style="text-decoration: none; font-family: 'Dancing Script', cursive; font-weight: bold; border: 2px solid currentColor; padding: 5px 15px; display: inline-block;"
                                class="text-dark"
                                title="Go Back">Back</a>
                        </h6>

                    </div>
                </div>
            </div>
            @endif
            @foreach($complain_detail as $complain_details)
            <div class="container1 mt-2 mb-2">
                <div class="row h-100">
                    <div class="col-4 first_section">
                        <a href="{{ route('product_checkout', ['id' => $complain_details->product->id]) }}" class="hide_anchor_style"><img src="{{ asset('storage/product_image/' . $complain_details->product->product_image) }}" alt="product_image" class="img-fluid"></a>
                    </div>
                    <div class="col-5 second_section">
                        <h5 class="mt-4"><a href="{{ route('product_checkout', ['id' => $complain_details->product->id]) }}" class="hide_anchor_style">{{ ucwords($complain_details->product->product_name)}}</a></h5>
                        <p class="text-primary d-none d-md-block">Category : {{ ucwords($complain_details->product->category_name) }}</p>
                        <p class="text-primary">Seller : Peak Pulse Market</p>
                        <h5>&#8377; {{$complain_details->sale_prices}}<span class="text-danger"> ({{$complain_details->total_order_quantity}} items)</span> </h5>
                    </div>
                    <div class="col-3 third_section">
                        <p class="text-success d-none d-sm-block"><strong>{{$complain_details->order_status_text}}</strong> on : {{$complain_details->updated_at->format('d-m-Y H:i:s')}}</p>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="container_complain mt-2 mb-2">
                <div class="row h-100">
                    <h5 class="text-center text-danger">Recent Messages Related to Helps</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="d-none d-sm-table-cell">S.No</th>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th class="d-none d-sm-table-cell">Complain Date (Latest First)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order_help_records as $index => $order_help)
                            <tr>
                                <td class="d-none d-sm-table-cell">{{ $index + 1 }}</td>
                                <td>{{ $order_help->subject }}</td>
                                <td>{{ $order_help->description }}</td>
                                <td>{{$order_help->order_help_status_text}}</td>
                                <td class="d-none d-sm-table-cell">{{ $order_help->created_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{--Show Spinner during page loading--}}
            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="{{url('js/spinner.js')}}"></script>
            </x-slot>
</x-dashboard_common>