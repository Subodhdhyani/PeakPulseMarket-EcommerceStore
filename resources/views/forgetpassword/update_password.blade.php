<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - PeakPulseMarket</title>
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
                            <div class="mb-3">
                                <input type="hidden" id="token" name="token" value="{{$token}}">
                                <input type="hidden" id="email" name="email" value="{{$email}}">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Enter New Password</label>
                                <input type="password" id="password" name="password" class="form-control" value="{{ old('password') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}" required>
                            </div>
                            <button type="submit" id="submit-button" class="btn btn-warning w-100">Reset Password</button>
                        </form>
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
            $('#submit-button').click(function(e) {
                e.preventDefault();
                $('#submit-button').prop('disabled', true); // Disable the button
                $('.spinner-container').show(); //show spinner
                var token = $('#token').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var password_confirmation = $('#password_confirmation').val();
                // Clear previous messages
                $('#response-message').html('');
                $.ajax({
                    url: "{{ route('password.update') }}",
                    type: 'POST',
                    data: {
                        token: token,
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation,
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
                            $('#submit-button').prop('disabled', false); // Re-enable the button
                            $('.spinner-container').hide(); //hide spinner
                        } else if (response.status === 'error') {
                            $('#response-message').html(`
                               <div class="alert alert-danger" role="alert">
                                ${response.message}
                              </div>
                            `);
                            $('#submit-button').prop('disabled', false); // Re-enable the button
                            $('.spinner-container').hide(); //hide spinner
                        } else if (response.status === 'validateerror') {
                            let errorMessages = '';
                            for (let field in response.message) {
                                response.message[field].forEach(error => {
                                    errorMessages += `
                                       <div class="alert alert-danger" role="alert">
                                         ${error}
                                       </div>
                                        `;
                                });

                            }
                            $('#response-message').html(errorMessages); // Display validation errors
                            $('#submit-button').prop('disabled', false); // Re-enable the button
                            $('.spinner-container').hide(); //hide spinner
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>