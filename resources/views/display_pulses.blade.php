<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pulses - PeakPulseMarket</title>
    @include('include.favicon')
    @include('include.fontawesome')
    @include('include.bootstrap')
    @include('include.spinner')
    <style>
        body {
            background-color: whitesmoke;
        }

        .container1 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: auto;
            row-gap: 35px;
            column-gap: 15px;
            margin-left: 6px;
        }

        .card {
            width: 18rem;
        }

        .card-text {
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            display: block;
        }

        .card a {
            text-decoration: none;
            /* Remove underline */
            color: inherit;
            /* Inherit color from parent */
        }

        .card:hover {
            box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.75);
            /*  Box-shadow on hover */
        }

        .rating-container {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .rating-value {
            background-color: #4caf50;
            color: #fff;
            padding: 2px 5px;
            border-radius: 5px;
            font-size: 12px;
        }

        @media (min-width: 50px) and (max-width: 765px) {
            .container1 {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: auto;
                row-gap: 15px;
                column-gap: 5px;
            }

            .card {
                width: 10rem;
                margin-left: 2%;
            }
        }

        @media (min-width: 768px) and (max-width: 1144px) {
            .container1 {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: auto;
                row-gap: 15px;
                column-gap: 5px;
            }

            .card {
                width: 16rem;
                margin-left: 2%;
            }
        }
    </style>
</head>

<body>
    {{--navbar--}}
    <x-navbar />
    <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}
        {{--This card is taken from Bootstrap Documentation--}}
        <h2 class="text-center mt-4 mb-4" style="color: #1fdb22;">Explorer Pulses <i class="fa-solid fa-angles-down fa-lg" style="color: #1fdb22;"></i></h2>
        <h5 class="text-center mt-4 no_record_class" style="display: none;">No Product Found.Updating Soon.....</h5>
        <div class="container1 mt-4 mb-4">
            {{-- Card Template (Hidden) Initially its hidden --}}

            <div class="card-template" style="display: none;">
                <div class="card">
                    <a href="" target="_blank">
                        <img src="{{ asset('category/pulse.jpeg') }}" class="card-img-top" alt="product_card_image">
                        <hr>
                        <div class="card-body">
                            <h5 class="card-title">Here Tiltle</h5>
                            <p class="card-text">Here Text</p>
                            <div class="rating-container">
                                <div class="rating-value"><span class="rating-fetch">No Rating</span> &#9733;</div>
                            </div>
                            <strong><span class="sale-price"> &#x20B9; 100</span> <del class="original-price">&#x20B9; 999</del> <span class="text-success discount-percentage">10% off</span></strong>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{--Footer--}}
    <x-footer />

    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
    <script>
        $(document).ready(function() {
            $('.spinner-container').show(); //show spinner
            $.ajax({
                url: '{{ route("fetch_pulses") }}',
                type: 'GET',
                success: function(response) {
                    $('.spinner-container').hide(); //hide the spinner
                    if (response.status === 'success') {
                        var fetch = response.message;
                        if (fetch.length === 0) {
                            $('.no_record_class').show();
                        } else {
                            fetch.forEach(function(record) {
                                var $cardTemplate = $('.card-template').clone().removeClass('card-template').show(); // Clone the card for each record
                                var imageUrl = "{{ asset('storage/product_image/') }}/" + record.product_image;
                                $cardTemplate.find('.card-img-top').attr('src', imageUrl);
                                $cardTemplate.find('.card-title').text(record.product_name);
                                // $cardTemplate.find('.card-text').text(record.description.substring(0, 30));
                                $cardTemplate.find('.card-text').text(record.description);
                                $cardTemplate.find('.sale-price').html('&#x20B9;' + record.sale_price);
                                $cardTemplate.find('.original-price').html('&#x20B9;' + record.original_price);
                                $cardTemplate.find('.discount-percentage').text(record.discount + '% off');
                                var productCheckoutUrl = '{{ route("product_checkout", ["id" => ":id"]) }}'.replace(':id', record.id);
                                $cardTemplate.find('a').attr('href', productCheckoutUrl);
                                // Display the average rating (fetched combiney with the product detais)
                                var rating = record.average_rating;
                                if (rating !== 'No Rating') {
                                    $cardTemplate.find('.rating-fetch').text(rating);
                                } else {
                                    $cardTemplate.find('.rating-fetch').text('No Rating');
                                }
                                $('.container1').append($cardTemplate);
                            });
                        }
                    }
                },
                error: function() {
                    $('.spinner-container').hide(); // spinner hides even if AJAX request fails
                    $('.no_record_class').text('Failed to load data. Please try again.').show();
                }
            });
        });
    </script>

</body>

</html>