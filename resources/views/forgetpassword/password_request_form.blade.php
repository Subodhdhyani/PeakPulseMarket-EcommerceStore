<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Password Reset - PeakPulseMarket</title>
    @include('include.favicon')
    @include('include.bootstrap')
    @include('include.fontawesome')
    @include('include.spinner')
</head>

<body class="bg-light">
    {{--navbar--}}
    <x-navbar />
    <div class="container mt-4 mb-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Reset Your Password</h4>
                    </div>
                    <div class="card-body">
                        <!-- Placeholder for Success or Error Message -->
                        <div id="response-message"></div>

                        <form autocomplete="off" id="reset-password-form">
                            <div class="mb-4">
                                <label for="email" class="form-label">Enter Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                <span class="text-danger email-error"></span>
                            </div>
                            <button type="submit" id="submit-button" class="btn btn-warning w-100">Send Password Reset Link</button>
                        </form>
                        <div class="text-center text-lg-start mt-2 pt-2 mb-4">
                            <p class="small fw-bold mt-2 pt-1 mb-0">Login to account? <a href="{{route('signin')}}"
                                    class="link-danger">Sign In</a></p>
                        </div>
                    </div>
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
            $('#reset-password-form').submit(function(e) {
                e.preventDefault();
                $('.spinner-container').show();
                $('#submit-button').prop('disabled', true); // Disable the button
                var email = $('#email').val();
                // Clear previous messages
                $('#response-message').html('');
                $('.email-error').text('');

                $.ajax({
                    url: "{{ route('sendResetLinkEmail') }}",
                    type: 'POST',
                    data: {
                        email: email,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Display success message
                        if (response.status === 'success') {
                            $('#response-message').html(`
                        <div class="alert alert-success" role="alert">
                            ${response.message}
                        </div>
                    `);
                            // Clear the form fields
                            $('#reset-password-form')[0].reset();
                            $('.spinner-container').hide();
                            $('#submit-button').prop('disabled', false); // Re-enable the button
                        }
                        // Display validation errors
                        else if (response.status === 'validateerror') {
                            var validateerror = response.message;
                            if (validateerror.email) {
                                // Display error message for email field
                                $('.email-error').text(validateerror.email[0]);
                            }
                            $('.spinner-container').hide();
                            $('#submit-button').prop('disabled', false); // Re-enable the button
                        } else if (response.status === 'error') {
                            $('#response-message').html(`
                        <div class="alert alert-danger" role="alert">
                            ${response.message}
                        </div>
                    `);
                            $('.spinner-container').hide();
                            $('#submit-button').prop('disabled', false); // Re-enable the button
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>