<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - PeakPulseMarket</title>
    @include('include.favicon')
    @include('include.bootstrap')
    @include('include.fontawesome')
    @include('include.spinner')
    <style>
        body {
            background-color: whitesmoke;
        }

        /*Category Style*/
        #main {
            height: auto;
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            background-color: whitesmoke;
        }

        .item {
            height: 100%;
            width: 30%;
            display: flex;
            flex-direction: column;
        }

        .content {
            margin: 0 auto;
            text-align: center;
        }

        .category_anchor {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            text-decoration: none;
            /* Remove underline */
            color: inherit;
            /* Inherit color from parent */
        }

        .category_image {
            max-height: 80%;
            max-width: 40%;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            #main {
                height: auto;
                display: flex;
                justify-content: space-between;
            }

            .item {
                width: 30%;
                height: auto;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .category_image {
                max-height: 90%;
                max-width: 90%;
            }
        }

        @media (min-width: 769px) {
            .category_image {
                padding: 10px 0;
            }
        }

        /*Carousel Style*/
        .carousel-item img {
            width: 100%;
            height: auto;
        }

        /* color of carousel control icons to dark */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #000;
        }

        @media (max-width: 768px) {
            .carousel-item {
                height: auto;
            }

            .carousel-item img {
                width: 100%;
                height: auto;
                object-fit: contain;
            }
        }


        .visually-hidden {
            color: #000;
        }

        /*For Recently Added Spices,Snacks etc container*/
        .recently-class {
            height: auto;
            width: 98%;
            background-color: white;
            margin-left: 1%;

        }

        .anchor {
            text-decoration: none;
            color: inherit;
            font-weight: bold;
            font-size: 28px;
        }

        /* card Styling for recently */
        .pulses_fetch,
        .spices_fetch,
        .pickles_fetch,
        .snacks_fetch {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: auto;
            /*row-gap: 35px;*/
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
            color: inherit;
        }

        .card:hover {
            box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.75);
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

            .pulses_fetch,
            .spices_fetch,
            .pickles_fetch,
            .snacks_fetch {
                display: grid;
                /*grid-template-columns: repeat(auto-fit, minmax(18rem, 1fr)); */
                grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr));
                /* Adjusted minimum width */
                grid-auto-flow: column;
                gap: 12px;
                overflow-x: auto;
            }

            .card {
                width: 14rem;
                margin-left: 5%;
            }
        }

        @media (min-width: 768px) and (max-width: 1144px) {

            .pulses_fetch,
            .spices_fetch,
            .pickles_fetch,
            .snacks_fetch {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: auto;
                row-gap: 15px;
                column-gap: 5px;
            }

            .pulses_fetch>div:nth-child(4),
            .spices_fetch>div:nth-child(4),
            .pickles_fetch>div:nth-child(4),
            .snacks_fetch>div:nth-child(4) {
                display: none;
            }

            .card {
                width: 15rem;
            }
        }
    </style>
</head>

<body>
    {{--navbar--}}
    <x-navbar />
    <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}
        {{--Category Div Here Category Fetch from Ajax--}}
        <div id="main"></div>

        {{--Carousel--}}
        <div class="container-fluid">
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="2000">
                        <div id="firstpic_show"></div>
                    </div>
                    <div class="carousel-item" data-bs-interval="2000">
                        <div id="secondpic_show"></div>
                    </div>
                    <div class="carousel-item" data-bs-interval="2000">
                        <div id="thirdpic_show"></div>
                        <div class="carousel-caption d-none d-md-block">
                            {{-- <h5 class="text-warning">Pure Origins</h5>
                    <p class="text-warning">Indulge in the authentic flavors of the Himalayas with our range of pure, organically sourced pulses. Every grain embodies the pristine essence of the majestic Himalayan mountains.</p>--}}
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>


        {{--Here The Recently Added all category Product--}}
        <div class="recently-class mt-4 mb-4">
            <a class="anchor" href="{{route('pulses')}}">Recently Added Pulses&nbsp;<i class="fa-solid fa-angle-right"></i></a>
            {{--This card is taken from Bootstrap Documentation--}}
            <h5 class="text-center mt-4 no_pulses_class" style="display: none;">No Recently Added Pulses</h5>
            <div class="pulses_fetch mt-4 mb-4">
                {{-- No card template needed here i fetch/created dynamically inside ajax success response--}}
            </div>
        </div>
        <div class="recently-class mt-4 mb-4">
            <a class="anchor" href="{{route('spices')}}">Recently Added Spices&nbsp;<i class="fa-solid fa-angle-right"></i></a>
            {{--This card is taken from Bootstrap Documentation--}}
            <h5 class="text-center mt-4 no_spices_class" style="display: none;">No Recently Added Spices</h5>
            <div class="spices_fetch mt-4 mb-4">
                {{-- No card template needed here i fetch/created dynamically inside ajax success response--}}
            </div>
        </div>
        <div class="recently-class mt-4 mb-4">
            <a class="anchor" href="{{route('pickles')}}">Recently Added Pickles&nbsp;<i class="fa-solid fa-angle-right"></i></a>
            {{--This card is taken from Bootstrap Documentation--}}
            <h5 class="text-center mt-4 no_pickles_class" style="display: none;">No Recently Added Pickles</h5>
            <div class="pickles_fetch mt-4 mb-4">
                {{-- No card template needed here i fetch/created dynamically inside ajax success response--}}
            </div>
        </div>
        <div class="recently-class mt-4 mb-4">
            <a class="anchor" href="{{route('snacks')}}">Recently Added Snacks&nbsp;<i class="fa-solid fa-angle-right"></i></a>
            {{--This card is taken from Bootstrap Documentation--}}
            <h5 class="text-center mt-4 no_snacks_class" style="display: none;">No Recently Added Snacks</h5>
            <div class="snacks_fetch mt-4 mb-4">
                {{-- No card template needed here i fetch/created dynamically inside ajax success response--}}
            </div>
        </div>
    </div>
    {{--Footer--}}
    <x-footer />

    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
    <script>
        // Function to get the route based on category name
        function getCategoryRoute(categoryName) {
            var route = '';
            switch (categoryName) {
                case 'pulse':
                    route = "{{ route('pulses') }}";
                    break;
                case 'spice':
                    route = "{{ route('spices') }}";
                    break;
                case 'pickle':
                    route = "{{ route('pickles') }}";
                    break;
                case 'snack':
                    route = "{{ route('snacks') }}";
                    break;
                default:
                    route = "#";
            }
            return route;
        }

        $(document).ready(function() {
            //For Category display at top
            $.ajax({
                url: '{{ route("display_category") }}',
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        var fetch = response.message;
                        if (fetch.length === 0) {
                            $('#main').html('<h3>No categories found</h3>');
                        } else {
                            // Loop to display all categories
                            fetch.forEach(function(category) {
                                var item = $('<div class="item"></div>'); // create a new item for each category

                                // Create the anchor tag to wrap the entire item
                                var anchor = $('<a class="category_anchor"></a>');
                                anchor.attr('href', getCategoryRoute(category.category_name)); // Set href attribute
                                anchor.attr('target', '_blank'); // Set target attribute to open in new tab

                                // If category image exists, then display it and wrap it with anchor
                                if (category.category_image) {
                                    var img = $('<img src="{{ asset("storage/category_image/") }}/' + category.category_image + '" class="d-block w-100 category_image" alt="Category Images">');
                                    anchor.append(img);
                                } else if (category.category_image === "" || category.category_image === null) {
                                    // If no image, display text
                                    anchor.append('<h3>No image found</h3>');
                                }

                                // Display category name and append to anchor
                                // anchor.append('<div class="content">' + category.category_name + '&ensp;<i class="fa-solid fa-chevron-right"></i>' + '</div>');

                                // Append the anchor tag to the item
                                item.append(anchor);
                                $('#main').append(item);
                            });
                        }
                    }
                }
            });

            //For carousel
            $.ajax({
                url: '{{ route("carousel_image") }}',
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        var fetch = response.message;
                        if (fetch === null) { //Here if we received complete table null
                            $('#firstpic_show').html('<h1>No image Found</h1>');
                            $('#secondpic_show').html('<h1>No image Found</h1>');
                            $('#thirdpic_show').html('<h1>No image Found</h1>');
                        } else {
                            if (fetch.firstpic) {
                                $('#firstpic_show').html('<img src="{{ asset("storage/front_content/") }}/' + fetch.firstpic + '" class="d-block w-100" alt="First Image">');
                            } else if (fetch.firstpic === null) { //if we receive specific image null i.e all other image found and this image not found
                                $('#firstpic_show').html('<h1>No image Found</h1>');
                            }
                            if (fetch.secondpic) {
                                $('#secondpic_show').html('<img src="{{ asset("storage/front_content/") }}/' + fetch.secondpic + '" class="d-block w-100" alt="Second Image">');
                            } else if (fetch.secondpic === null) {
                                $('#secondpic_show').html('<h1>No image Found</h1>');
                            }
                            if (fetch.thirdpic) {
                                $('#thirdpic_show').html('<img src="{{ asset("storage/front_content/") }}/' + fetch.thirdpic + '" class="d-block w-100" alt="Third Image">');
                            } else if (fetch.thirdpic === null) {
                                $('#thirdpic_show').html('<h1>No image Found</h1>');
                            }
                        }
                    }
                }
            });

            //For Recently Pulses Fetch
            $.ajax({
                url: '{{ route("fetch_pulses_home") }}',
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        var fetch = response.message;
                        if (fetch.length === 0) {
                            $('.no_pulses_class').show();
                        } else {
                            // Iterate over each record and create card dynamically
                            fetch.forEach(function(record) {
                                var $card = $('<div class="card mb-4"></div>');
                                var $cardLink = $('<a href="" target="_blank"></a>');
                                var $cardImg = $('<img class="card-img-top" alt="product_card_image">').attr('src', "{{ asset('storage/product_image/') }}/" + record.product_image);
                                var $hr = $('<hr>');
                                var $cardBody = $('<div class="card-body"></div>');
                                var $cardTitle = $('<h5 class="card-title"></h5>').text(record.product_name);
                                var $cardText = $('<p class="card-text"></p>').text(record.description);
                                var $ratingContainer = $('<div class="rating-container"></div>');
                                var $ratingValue = $('<div class="rating-value">' + record.average_rating + '<span> &#9733;</span></div>');
                                var $strong = $('<strong></strong>');
                                var space = '&nbsp;';
                                var $originalPrice = $('<span class="sale-price">&#x20B9;' + record.sale_price + '</span>');
                                var $salePrice = $('<del class="original-price">&#x20B9;' + record.original_price + '</del>');
                                var $discountPercentage = $('<span class="text-success discount-percentage">' + record.discount + '% off</span>');

                                $strong.append($originalPrice, space, $salePrice, space, $discountPercentage);
                                $ratingContainer.append($ratingValue);
                                $cardBody.append($cardTitle, $cardText, $ratingContainer, $strong);
                                //fetch id and link to anchor tag with route
                                $cardLink.attr('href', "{{ route('product_checkout', ':id') }}".replace(':id', record.id));
                                $cardLink.append($cardImg, $hr, $cardBody);
                                $card.append($cardLink);

                                // Append the created card to the container
                                $('.pulses_fetch').append($card);
                            });
                        }
                    } else {
                        console.error("Error: " + response.message);
                    }
                }
            });

            //For Recently Spices Fetch
            $.ajax({
                url: '{{ route("fetch_spices_home") }}',
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        var fetch = response.message;
                        if (fetch.length === 0) {
                            $('.no_spices_class').show();
                        } else {
                            // Iterate over each record and create card dynamically
                            fetch.forEach(function(record) {
                                var $card = $('<div class="card mb-4"></div>');
                                var $cardLink = $('<a href="" target="_blank"></a>');
                                var $cardImg = $('<img class="card-img-top" alt="product_card_image">').attr('src', "{{ asset('storage/product_image/') }}/" + record.product_image);
                                var $hr = $('<hr>');
                                var $cardBody = $('<div class="card-body"></div>');
                                var $cardTitle = $('<h5 class="card-title"></h5>').text(record.product_name);
                                var $cardText = $('<p class="card-text"></p>').text(record.description);
                                var $ratingContainer = $('<div class="rating-container"></div>');
                                var $ratingValue = $('<div class="rating-value">' + record.average_rating + '<span> &#9733;</span></div>');
                                var $strong = $('<strong></strong>');
                                var space = '&nbsp;';
                                var $originalPrice = $('<span class="original-price">&#x20B9;' + record.sale_price + '</span>');
                                var $salePrice = $('<del class="sale-price">&#x20B9;' + record.original_price + '</del>');
                                var $discountPercentage = $('<span class="text-success discount-percentage">' + record.discount + '% off</span>');

                                $strong.append($originalPrice, space, $salePrice, space, $discountPercentage);
                                $ratingContainer.append($ratingValue);
                                $cardBody.append($cardTitle, $cardText, $ratingContainer, $strong);
                                //fetch id and link to anchor tag with route
                                $cardLink.attr('href', "{{ route('product_checkout', ':id') }}".replace(':id', record.id));
                                $cardLink.append($cardImg, $hr, $cardBody);
                                $card.append($cardLink);

                                // Append the created card to the container
                                $('.spices_fetch').append($card);
                            });
                        }
                    } else {
                        console.error("Error: " + response.message);
                    }
                }
            });

            //For Recently Pickles Fetch
            $.ajax({
                url: '{{ route("fetch_pickles_home") }}',
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        var fetch = response.message;
                        if (fetch.length === 0) {
                            $('.no_pickles_class').show();
                        } else {
                            // Iterate over each record and create card dynamically
                            fetch.forEach(function(record) {
                                var $card = $('<div class="card mb-4"></div>');
                                var $cardLink = $('<a href="" target="_blank"></a>');
                                var $cardImg = $('<img class="card-img-top" alt="product_card_image">').attr('src', "{{ asset('storage/product_image/') }}/" + record.product_image);
                                var $hr = $('<hr>');
                                var $cardBody = $('<div class="card-body"></div>');
                                var $cardTitle = $('<h5 class="card-title"></h5>').text(record.product_name);
                                var $cardText = $('<p class="card-text"></p>').text(record.description);
                                var $ratingContainer = $('<div class="rating-container"></div>');
                                var $ratingValue = $('<div class="rating-value">' + record.average_rating + '<span> &#9733;</span></div>');
                                var $strong = $('<strong></strong>');
                                var space = '&nbsp;';
                                var $originalPrice = $('<span class="original-price">&#x20B9;' + record.sale_price + '</span>');
                                var $salePrice = $('<del class="sale-price">&#x20B9;' + record.original_price + '</del>');
                                var $discountPercentage = $('<span class="text-success discount-percentage">' + record.discount + '% off</span>');

                                $strong.append($originalPrice, space, $salePrice, space, $discountPercentage);
                                $ratingContainer.append($ratingValue);
                                $cardBody.append($cardTitle, $cardText, $ratingContainer, $strong);
                                //fetch id and link to anchor tag with route
                                $cardLink.attr('href', "{{ route('product_checkout', ':id') }}".replace(':id', record.id));
                                $cardLink.append($cardImg, $hr, $cardBody);
                                $card.append($cardLink);

                                // Append the created card to the container
                                $('.pickles_fetch').append($card);
                            });
                        }
                    } else {
                        console.error("Error: " + response.message);
                    }
                }
            });

            //For Recently Snacks Fetch
            $.ajax({
                url: '{{ route("fetch_snacks_home") }}',
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        var fetch = response.message;
                        if (fetch.length === 0) {
                            $('.no_snacks_class').show();
                        } else {
                            // Iterate over each record and create card dynamically
                            fetch.forEach(function(record) {
                                var $card = $('<div class="card mb-4"></div>');
                                var $cardLink = $('<a href="" target="_blank"></a>');
                                var $cardImg = $('<img class="card-img-top" alt="product_card_image">').attr('src', "{{ asset('storage/product_image/') }}/" + record.product_image);
                                var $hr = $('<hr>');
                                var $cardBody = $('<div class="card-body"></div>');
                                var $cardTitle = $('<h5 class="card-title"></h5>').text(record.product_name);
                                var $cardText = $('<p class="card-text"></p>').text(record.description);
                                var $ratingContainer = $('<div class="rating-container"></div>');
                                var $ratingValue = $('<div class="rating-value">' + record.average_rating + '<span> &#9733;</span></div>');
                                var $strong = $('<strong></strong>');
                                var space = '&nbsp;';
                                var $originalPrice = $('<span class="original-price">&#x20B9;' + record.sale_price + '</span>');
                                var $salePrice = $('<del class="sale-price">&#x20B9;' + record.original_price + '</del>');
                                var $discountPercentage = $('<span class="text-success discount-percentage">' + record.discount + '% off</span>');

                                $strong.append($originalPrice, space, $salePrice, space, $discountPercentage);
                                $ratingContainer.append($ratingValue);
                                $cardBody.append($cardTitle, $cardText, $ratingContainer, $strong);
                                //fetch id and link to anchor tag with route
                                $cardLink.attr('href', "{{ route('product_checkout', ':id') }}".replace(':id', record.id));
                                $cardLink.append($cardImg, $hr, $cardBody);
                                $card.append($cardLink);

                                // Append the created card to the container
                                $('.snacks_fetch').append($card);
                            });
                        }
                    } else {
                        console.error("Error: " + response.message);
                    }
                }
            });

        });
    </script>
</body>

</html>