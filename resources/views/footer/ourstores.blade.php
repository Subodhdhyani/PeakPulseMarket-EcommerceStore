<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Stores - PeakPulseMarket</title>
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
                <h1 class="text-center">Our Store</h1>
                <p>In addition to our online presence, we are thrilled to announce the opening of our flagship offline store, located at <strong>1st Floor Pacific Mall Dehradun,Uttarakhand</strong> , where customers can experience the essence of Peak Pulse Market in a physical setting.<br><br>
                    Our offline store offers a unique shopping experience, allowing customers to explore our range of pulses, pickles, snacks, and spices in person, with knowledgeable staff on hand to provide expert advice and recommendations.<br><br>
                    We are excited to bring the convenience and quality of Peak Pulse Market to a new dimension, welcoming customers to immerse themselves in our products and enjoy a personalized shopping experience. <br><br>
                <h5>Visit us at our offline store and discover the true taste of Peak Pulse Market!</h5>
                </p>


                <div class="d-flex justify-content-center align-items-center">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3442.4537902733427!2d78.0677372738393!3d30.366469603244575!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3908d7cbdc6e9d1b%3A0x7082f9fac370bdb7!2sPacific%20Mall%20Dehradun!5e0!3m2!1sen!2sin!4v1714975876494!5m2!1sen!2sin" width="70%" height="200vh" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
    <x-footer />
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
</body>

</html>