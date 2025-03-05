<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - PeakPulseMarket</title>
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
                <h1 class="text-center">About Peak Pulse Market</h1>
                <p>
                    <strong>PeakPulseMarket.com is the official online e-commerce platform of Peak Pulse Internet Private Limited, a company incorporated to serve customers with excellence since 2025. Our registered office is situated at Himalayan Tower, IT Park, Sahastradhara Rd, Dehradun, Uttarakhand, India, 248001.</strong><br><br>
                    At Peak Pulse Market, we are committed to providing a seamless shopping experience, offering a comprehensive range of premium products at competitive prices.<br><br>
                    Our mission is to deliver the best service, top-notch quality products, and real-time assistance to our valued customers. With just a few clicks, you can access our full product range,all conveniently delivered to your doorstep. Experience the convenience of online shopping with Peak Pulse Market – your trusted destination for quality products and exceptional service.<br><br>
                    <strong>PeakPulseMarket.com</strong> is the premier destination for organic and pure pulses, spices, snacks, and pickles. As a trusted online e-commerce platform operated by Peak Pulse Internet Private Limited, we are dedicated to providing customers with the finest quality products sourced from reputable suppliers.<br><br>
                    At Peak Pulse Market, we are committed to excellence in customer service, ensuring that your online shopping experience is smooth and hassle-free. With secure payment options and prompt delivery to your doorstep, we strive to exceed your expectations every step of the way.<br><br>
                </p>
            </div>
        </div>

    </div>
    <x-footer />
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}

</body>

</html>