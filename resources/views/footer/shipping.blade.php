<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping - PeakPulseMarket</title>
    @include('include.favicon')
    @include('include.fontawesome')
    @include('include.bootstrap')
    @include('include.spinner')
    <style>
        body {
            background-color: #f8f9fa;
            /* Set background color */
        }

        .container-box {
            width: 100%;
            height: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
            /* Set background color */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Add a subtle box shadow */
        }
    </style>
</head>

<body>
    {{--navbar--}}
    <x-navbar />
    <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}
        <div class="container mb-4 mt-4">
            <div class="container-box">
                <h1 class="text-center">Shipping Information for Peak Pulse Market</h1>
                <p>

                    <strong>Delivery Charges:</strong>
                    Delivery charges vary depending on the location. For orders above Rs. 1000, delivery is free. Additional delivery charges may apply based on your location, and these will be displayed at the checkout.<br>

                    <strong>Estimated Delivery Time:</strong>
                    The delivery time is influenced by factors such as product availability, seller location, and courier partner's delivery timeline. Generally, sellers procure and ship items within the specified time mentioned on the product page.<br>

                    <strong>Delivery Partnerships:</strong>
                    We have partnered with various reliable delivery companies across India to ensure smooth and efficient shipping of your orders. However, please note that we currently do not offer international shipping.<br>

                    <strong>Cash on Delivery (CoD):</strong>
                    The availability of Cash on Delivery (CoD) depends on our courier partner's serviceability in your location and their ability to accept cash payments upon delivery. Please check the product page for CoD availability in your area.<br>

                    <strong>Returns and Pick-Up:</strong>
                    Returning items is easy. Simply contact us to initiate a return, and we will guide you through the process. Our Logistics Partner will facilitate the pick-up wherever possible.<br>

                    <strong>Order Tracking:</strong>
                    Once your order is confirmed, you'll receive tracking information to monitor the delivery status of your package.<br>

                    <strong>International Delivery:</strong>
                    At the moment, we only deliver within India. However, you can make purchases from anywhere in the world using credit/debit cards issued in India and select countries.<br><br>

                    For any further inquiries or assistance, please don't hesitate to contact us.<br>
                </p>
            </div>
        </div>
    </div>

    <x-footer />
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
</body>

</html>