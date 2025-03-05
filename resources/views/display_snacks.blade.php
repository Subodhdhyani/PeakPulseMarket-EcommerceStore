<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snacks - PeakPulseMarket</title>
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
            /* Add box-shadow on hover */
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
        <h2 class="text-center mt-4 mb-4" style="color: #1fdb22;">Explorer Snacks <i class="fa-solid fa-angles-down fa-lg" style="color: #1fdb22;"></i></h2>
        <h5 class="text-center mt-4 no_record_class" style="display: none;">No Product Found.Updating Soon.....</h5>
        <div class="container1 mt-4 mb-4">
            {{-- No card template needed here i fetch/created dynamically inside ajax success response--}}
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
                url: '{{ route("fetch_snacks") }}',
                type: 'GET',
                success: function(response) {
                    $('.spinner-container').hide(); //hide the spinner
                    if (response.status === 'success') {
                        var fetch = response.message;
                        if (fetch.length === 0) {
                            $('.no_record_class').show();
                        } else {
                            // Iterate over each record and create card dynamically
                            fetch.forEach(function(record) {
                                var $card = $('<div class="card"></div>');
                                var $cardLink = $('<a href="" target="_blank"></a>');
                                var $cardImg = $('<img class="card-img-top" alt="product_card_image">').attr('src', "{{ asset('storage/product_image/') }}/" + record.product_image);
                                var $hr = $('<hr>');
                                var $cardBody = $('<div class="card-body"></div>');
                                var $cardTitle = $('<h5 class="card-title"></h5>').text(record.product_name);
                                //var $cardText = $('<p class="card-text"></p>').text(record.description.substring(0, 35));
                                var $cardText = $('<p class="card-text"></p>').text(record.description);
                                var $ratingContainer = $('<div class="rating-container"></div>');
                                var $ratingValue = $('<div class="rating-value">' + record.average_rating + '<span> &#9733;</span></div>');
                                var $strong = $('<strong></strong>');
                                var space = '&nbsp;';                               
                                var $salePrice = $('<span class="sale-price">&#x20B9;' + record.sale_price + '</span>');
                                var $originalPrice = $('<del class="original-price">&#x20B9;' + record.original_price + '</del>');
                                var $discountPercentage = $('<span class="text-success discount-percentage">' + record.discount + '% off</span>');

                                $strong.append($salePrice, space, $originalPrice, space, $discountPercentage);
                                $ratingContainer.append($ratingValue);
                                $cardBody.append($cardTitle, $cardText, $ratingContainer, $strong);
                                //fetch id and link to anchor tag with route
                                $cardLink.attr('href', "{{ route('product_checkout', ':id') }}".replace(':id', record.id));
                                $cardLink.append($cardImg, $hr, $cardBody);
                                $card.append($cardLink);

                                // Append the created card to the container
                                $('.container1').append($card);
                            });
                        }
                    } else {
                        console.error("Error: " + response.message);
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