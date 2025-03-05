<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers - PeakPulseMarket</title>
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
            width: 90%;
            height: auto;
            padding: 20px;
            margin: 0 5%;
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
        <div class="container-box mt-4 mb-4">
            <h3 class="text-center">Join Our Team</h3>
            <p>At <strong>Peak Pulse Market,</strong> we believe in the power of talent. As an online e-commerce store, we are constantly evolving and expanding, and we're always on the lookout for passionate individuals to join our team and contribute to our success.</p>

            <h3 class="text-center">Why Choose Us?</h3>
            <p><strong>Dynamic Environment:</strong>Join a dynamic and innovative team that embraces creativity and collaboration.<br>
                <strong>Opportunity for Growth:</strong> Explore endless opportunities for personal and professional growth in a fast-paced industry.<br>
                <strong>Make an Impact:</strong> Contribute to a company that values your ideas and encourages you to make a difference.
            <p>

            <h3 class="text-center">How to Apply</h3>
            <p>If you're ready to embark on a rewarding career journey with us, we invite you to share your resume with us at <strong>careers@peakpulsemarket.com</strong>. Whether you're a seasoned professional or a fresh graduate, we welcome diversity and are eager to learn about your unique skills and experiences.<br>
            <h5 class="mb-4">Join us today and be a part of something extraordinary!</h5>
            </p>

            <h3 class="text-center border-top border-dark">A journey we are proud to be on</h3>
            <h5 class="text-center">These recognitions are a testament to our people-centric culture that enables us to maximise. And we're nowhere near done yet</h5>
            <div class="mb-4">
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('career/ppm_Award_Logos_Linkedin.png') }}" alt="image" class="img-fluid" style="height: 300px; width: 300px;">
                    </div>
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('career/ppm_BusinessToday.jpg') }}" alt="image" class="img-fluid" style="height: 80px; width: 200px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('career/ppm_Economicstimes.jpg') }}" alt="image" class="img-fluid" style="height: 80px; width: 200px;">
                    </div>
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('career/ppm_Award_Logos_Avtar.png') }}" alt="image" class="img-fluid" style="height: 150px; width: 300px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('career/ppm_Award_Logos_Fairwork.png') }}" alt="image" class="img-fluid" style="height: 100px; width: 300px;">
                    </div>
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('career/ppm_Award_Logos_GPTW.png') }}" alt="image" class="img-fluid" style="height: 250px; width: 150px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('career/ppm_GPTW_Green.png') }}" alt="image" class="img-fluid" style="height: 200px; width: 200px;">
                    </div>
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('career/ppm_Great_Managers_Award.jpg') }}" alt="image" class="img-fluid" style="height: 100px; width: 200px;">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <x-footer />
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
</body>

</html>