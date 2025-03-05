<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Signin - PeakPulseMarket</title>
  @include('include.favicon')
  @include('include.bootstrap')
  @include('include.fontawesome')
  @include('include.spinner')
  <style>
    input[type="email"],
    input[type="password"] {
      background-color: aliceblue;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      background-color: aliceblue;
    }

    .divider:after,
    .divider:before {
      /*for OR after Signin with facebookk etc icon at top*/
      content: "";
      flex: 1;
      height: 2px;
      background: black;
    }

    .h-custom {
      height: calc(100% - 73px);
    }

    @media (max-width: 450px) {
      .h-custom {
        height: 100%;
      }
    }
  </style>
</head>

<body>

  {{--navbar--}}
  <x-navbar />
  <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}
    <section class="vh-50 mt-4 mb-4">
      <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-lg-6 col-xl-4 d-none d-lg-block">
            <img src="{{asset('Business_Logo/logo1.png')}}" class="img-fluid" alt="Business image">
          </div>
          <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <form id="myform" autocomplete="off">
              <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                <p class="lead fw-normal mb-0 me-3">Sign In in with</p>
                <a href="#" class="btn btn-warning btn-floating mx-1">
                  <i class="fab fa-facebook-f"></i>
                </a>

                <a href="#" class="btn btn-warning btn-floating mx-1">
                  <i class="fab fa-google"></i>
                </a>

                <a href="#" class="btn btn-warning btn-floating mx-1">
                  <i class="fab fa-linkedin-in"></i>
                </a>
              </div>

              <div class="divider d-flex align-items-center my-4">
                <p class="text-center fw-bold mx-3 mb-0">Or</p>
              </div>
              <!-- Error Display when credentials not match -->
              <div id="error_display" class="alert alert-danger" style="display: none; background-color: red;color: white;"></div>
              <!-- Email input -->
              <div class="form-outline mb-4">
                <label class="form-label" for="email">Email address</label>
                <input type="email" id="email" name="email" class="form-control form-control-lg"
                  placeholder="Enter a valid email address" value="{{old('email')}}" required />
                <span class="signin_error" id="email_error" style="color: red;"></span>
              </div>

              <!-- Password input -->
              <div class="form-outline mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control form-control-lg"
                  placeholder="Enter password" value="{{old('password')}}" required />
                <span class="signin_error" id="password_error" style="color: red;"></span>
              </div>

              <div class="d-flex justify-content-between align-items-center">
                <!-- Checkbox -->
                <div class="form-check mb-0">
                  <input class="form-check-input me-2" type="checkbox" value="1" id="checkbox" name="checkbox" />
                  <label class="form-check-label" for="checkbox">
                    Remember me
                  </label>
                </div>
                <a href="{{route('password.request')}}" class="text-body">Forgot password?</a>
              </div>

              <div class="text-center text-lg-start mt-4 pt-2">
                <button id="submit_form" class="btn btn-warning btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;">
                  Sign In
                </button>
                <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="{{route('signup')}}"
                    class="link-danger">Register</a></p>
              </div>

            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
  {{--Footer--}}
  <x-footer />

  <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
  <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
  <script>
    $(document).ready(function() {
      $('#submit_form').click(function(e) {
        e.preventDefault();
        $('.spinner-container').show();
        $('#submit_form').prop('disabled', true); // Disable the button
        var email = $('#email').val();
        var password = $('#password').val();
        var checkbox = $('#checkbox').is(':checked') ? 1 : 0; // Sends '1' if checked, otherwise '0'
        $.ajax({
          url: "{{route('signinreq')}}",
          type: 'POST',
          data: {
            email: email,
            password: password,
            checkbox: checkbox
          },
          // contentType: false,  Not given on when fetch value and serialize only used with file and then myform
          //processData: false, 
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {

            if (response.status === 'success') {
              window.location.href = response.message_redirect_url;

            } else if (response.status === 'block_error') {
              $('#error_display').html(response.message).show();
              $('.spinner-container').hide();
              $('#submit_form').prop('disabled', false); // Re-enable the button
            } else if (response.status === 'error') {
              $('#error_display').html(response.message).show();
              $('.spinner-container').hide();
              $('#submit_form').prop('disabled', false); // Re-enable the button
            } else if (response.status === 'validateerror') {
              $('.signin_error').text('');
              var errors = response.message;
              $.each(errors, function(key, value) {
                $('#' + key + '_error').text(value[0]);
              });
              $('.spinner-container').hide();
              $('#submit_form').prop('disabled', false); // Re-enable the button
            }

          },

        });

      });
    });
  </script>
</body>

</html>