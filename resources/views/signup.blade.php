<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Signup - PeakPulseMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @include('include.favicon')
    @include('include.bootstrap')
    @include('include.fontawesome')
    @include('include.spinner')
    <style>
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="text"] {
            background-color: aliceblue;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus,
        input[type="text"]:focus {
            background-color: aliceblue;
        }

        .h-custom {
            height: calc(100% - 73px);
        }

        .toastr-success {
            background-color: green !important;
        }

        .toastr-error {
            background-color: red !important;
        }

        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }

        .otp-form-container {
            display: none;
        }
    </style>
</head>

<body>

    {{-- Navbar --}}
    <x-navbar />
    <div class="dynamic-search-result-show-then-hide-this">
        {{-- Inside navbar Component --}}
        <section class="vh-50 mt-4 mb-4">
            <div class="container-fluid h-custom">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-lg-6 col-xl-4 d-none d-lg-block">
                        <img src="{{asset('Business_Logo/logo1.png')}}" class="img-fluid" alt="Business image">
                    </div>

                    <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                        <p class="lead fw-normal mb-4 me-3">Sign Up & Start Shopping</p>
                        <hr style="border-top: 2px solid black; margin: 10px 0;">
                        {{-- Signup Form --}}
                        <form id="myform" autocomplete="off">
                            <div class="form-outline mb-4 mt-4">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control form-control-lg"
                                    placeholder="Enter Name" value="{{old('name')}}" required />
                                <span class="signup_error" id="name_error" style="color: red;"></span>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="email">Email address</label>
                                <input type="email" id="email" name="email" class="form-control form-control-lg"
                                    placeholder="Enter a valid email address" value="{{old('email')}}" required />
                                <span class="signup_error" id="email_error" style="color: red;"></span>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="mobile">Mobile No.</label>
                                <input type="number" id="mobile" name="mobile" class="form-control form-control-lg"
                                    placeholder="Enter a valid Mobile No." value="{{old('mobile')}}" />
                                <span class="signup_error" id="mobile_error" style="color: red;"></span>
                            </div>

                            <div class="form-outline mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control form-control-lg"
                                    placeholder="Enter password" value="{{old('password')}}" required />
                                <span class="signup_error" id="password_error" style="color: red;"></span>
                            </div>

                            <div class="text-center text-lg-start mt-4 pt-2">
                                <button id="submit_form" class="btn btn-warning btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">
                                    Sign Up
                                </button>
                                <p class="small fw-bold mt-2 pt-1 mb-0">Already have an account? <a href="{{route('signin')}}"
                                        class="link-danger">Sign In</a></p>
                            </div>
                        </form>

                        {{-- OTP Verification Form (Initially hidden) --}}
                        <form id="otp_form" class="otp-form-container" autocomplete="off">
                            <div class="form-outline mb-4">
                                <label class="form-label" for="otp">Enter OTP</label>
                                <input type="text" id="otp" name="otp" class="form-control form-control-lg"
                                    placeholder="Enter OTP" required />
                            </div>

                            <div class="text-center text-lg-start mt-4 pt-2">
                                <button id="submit_otp" class="btn btn-warning btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">
                                    Verify OTP
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Footer --}}
    <x-footer />

    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#submit_form').click(function(e) {
                e.preventDefault();
                // Show the spinner when the form is being processed
                $('.spinner-container').show();
                $('#submit_form').prop('disabled', true); // Disable the button
                var name = $('#name').val();
                var email = $('#email').val();
                var mobile = $('#mobile').val();
                var password = $('#password').val();

                $.ajax({
                    url: "{{route('signupreq')}}",
                    type: 'POST',
                    data: {
                        name: name,
                        email: email,
                        mobile: mobile,
                        password: password
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#myform').trigger('reset');
                            toastr.success(response.message, "", {
                                "toastClass": "toastr-success"
                            });
                            $('.spinner-container').hide();
                            $('#submit_form').prop('disabled', false); //Re-enable the button

                            // Hide the signup form and show the OTP form
                            $('#myform').hide();
                            $('.otp-form-container').show();
                        } else if (response.status === 'error') {
                            toastr.error(response.message, "", {
                                "toastClass": "toastr-error"
                            });
                            $('#myform').trigger('reset');
                            $('.spinner-container').hide();
                            $('#submit_form').prop('disabled', false); //Re-enable the button
                        } else if (response.status === 'validateerror') {
                            $('.spinner-container').hide();
                            $('#submit_form').prop('disabled', false); //Re-enable the button
                            $('.signup_error').text('');
                            var errors = response.message;
                            $.each(errors, function(key, value) {
                                $('#' + key + '_error').text(value[0]);
                            });
                        }
                    },
                });
            });

            $('#submit_otp').click(function(e) {
                e.preventDefault();
                var otp = $('#otp').val();
                $('#submit_otp').prop('disabled', true); // Disable the button
                $('.spinner-container').show();
                // Make an AJAX call to verify the OTP here
                $.ajax({
                    url: "{{route('verifyOtp')}}",
                    type: 'POST',
                    data: {
                        otp: otp
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message, "", {
                                "toastClass": "toastr-success"
                            });
                            $('#submit_otp').prop('disabled', false); //Re-enable the button
                            $('.spinner-container').hide();
                            // Redirect to the provided URL from the backend
                            // Redirect after the toastr success message
                            setTimeout(function() {
                                window.location.href = response.redirect_url; // Perform redirect after the success message
                            }, 1000); // Adjust delay if needed
                        } else {
                            toastr.error(response.message, "", {
                                "toastClass": "toastr-error"
                            });
                            $('#submit_otp').prop('disabled', false); //Re-enable the button
                            $('.spinner-container').hide();
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>