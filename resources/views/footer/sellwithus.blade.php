<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sell With Us - PeakPulseMarket</title>
    @include('include.favicon')
    @include('include.fontawesome')
    @include('include.bootstrap')
    @include('include.spinner')
    <style>
        body {
            background-color: #f8f9fa;
            /* Set background color */
        }

        .custom-div {
            width: 80%;
            margin: 0 10%;
            padding: 20px;
            background-color: #fff;
            /* Set background color */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Add a subtle box shadow */
            height: auto;
            border-radius: 12px;
        }

        .submit_error {
            color: red;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>

    {{--navbar--}}
    <x-navbar />
    <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}
        {{-- Display success or error message after form filled --}}
        <div class="container mt-4 mb-4">
            {{-- Placeholder for AJAX response messages --}}
            <div id="ajax-message" class="alert" style="display: none;"></div>
        </div>


        <div class="custom-div mb-4 mt-4">
            <h4 class="text-warning text-center">Sell with us</h4>
            <h6>
                Do you have delicious pulses, spices, pickles, or snacks products that you'd like to share with our valuable customers? Join Peak Pulse Market and become a valued seller on our platform!<br><br>
                We offers a hassle-free way for you to showcase your unique products to a broader audience. Simply fill out form below with your details and product information, and we'll be in touch to discuss how we can collaborate.<br><br>
                Let's spread the joy of authentic hill area flavors together. Join us and be a part of the Peak Pulse Market family!<br><br>
            </h6>
            <form id="sell_form" autocomplete="off">
                <div class="row g-2 mt-2">
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" required>
                            <label for="name">Name</label>
                            <span class="submit_error" id="name_error"></span>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="subject" name="subject" value="{{old('subject')}}" required>
                            <label for="subject">Subject</label>
                            <span class="submit_error" id="subject_error"></span>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md">
                        <div class="form-floating">
                            {{-- <input type="number" class="form-control" id="phone" name="phone" value="{{old('phone')}}" required> --}}
                            <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" value="{{old('phone')}}" required>
                            <label for="phone">Phone</label>
                            <span class="submit_error" id="phone_error"></span>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" required>
                            <label for="email">Email</label>
                            <span class="submit_error" id="email_error"></span>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="product" name="product" value="{{old('product')}}" required>
                            <label for="product">Product Name</label>
                            <span class="submit_error" id="product_error"></span>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="{{old('quantity')}}" required>
                            <label for="quantity">Product Quantity</label>
                            <span class="submit_error" id="quantity_error"></span>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="form-floating">
                        <textarea class="form-control" id="description" name="description" style="height: 150px" required>{{ old('description') }}</textarea>
                        <label for="description">Enter Product Description</label>
                        <span class="submit_error" id="description_error"></span>
                    </div>
                </div>

                <div class="col-12 mt-2">
                    <input type="submit" id="submit" class="btn btn-warning" value="Submit Detail">
                </div>


            </form>
        </div>

    </div>
    <x-footer />
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when form load--}}
    <script>
        $(document).ready(function() {
            //Send form data
            $('#sell_form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $('#submit').prop('disabled', true).val('Submiting.....'); //disable button to protect multiple click
                $('.spinner-container').show(); //show spinner

                $.ajax({
                    url: "{{ route('sellwithusform') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('.submit_error').text(''); //clear any previous error message
                        //Clear any previous alert classes (success or danger)
                        $('#ajax-message').hide().removeClass('alert-success alert-danger');

                        if (response.status === 'validateerror') {
                            // Handle validation errors
                            $.each(response.message, function(key, value) {
                                $('#' + key + '_error').text(value[0]);
                            });
                        } else if (response.status === 'success') {
                            // Show success message
                            $('#ajax-message')
                                .addClass('alert alert-success')
                                .text(response.message)
                                .fadeIn();

                            // Reset the form
                            $('#sell_form')[0].reset();
                        } else if (response.status === 'error') {
                            // Show error message
                            $('#ajax-message')
                                .addClass('alert alert-danger')
                                .text(response.message)
                                .fadeIn();
                        }
                    },
                    error: function() {
                        // In case of any AJAX error, enable the submit button again
                        $('#submit').prop('disabled', false).val('Submit Details');
                        $('#ajax-message')
                            .addClass('alert alert-danger')
                            .text('An error occurred while submitting the form.')
                            .fadeIn();
                        $('.spinner-container').hide(); //hide the spinner
                    },
                    complete: function() {
                        // Always enable the submit button again when done
                        $('#submit').prop('disabled', false).val('Submit Details');
                        $('.spinner-container').hide(); //hide the spinner
                    }
                });
            });



        });
    </script>
</body>

</html>