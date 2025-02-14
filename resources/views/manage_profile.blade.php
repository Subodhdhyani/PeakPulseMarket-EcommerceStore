<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Profile - PeakPulseMarket</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <meta name="csrf-token" content="{{ csrf_token() }}"><!--the second one is variable and the value comes inside this variable is from ajax-->
  @include('include.favicon')
  @include('include.bootstrap')
  @include('include.fontawesome')
  @include('include.spinner')
  <style>
    fieldset {
      margin-bottom: 1em !important;
      border: 1px solid #666 !important;
      padding: 1px !important;
    }

    legend {
      padding: 1px 10px !important;
      float: none;
      width: auto;
    }

    .toastr-success {
      background-color: green !important;
    }

    .toastr-error {
      background-color: red !important;
    }

    .otp-form-container {
      display: none;
    }
  </style>
</head>

<body>
  {{--navbar--}}
  <x-navbar />
  <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}
    <div class="container-fluid">
      <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-warning">
          <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-dark min-vh-50">
            <a href="#" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
              <span class="fs-5 d-none d-sm-inline text-dark">Menu</span>
            </a>
            <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
              <li class="nav-item">
                <a href="{{route('manage_profile')}}" class="nav-link align-middle px-0 text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Profile"><i class="bi bi-person-square text-dark"></i> <span class="ms-1 d-none d-sm-inline text-dark fw-bold">Manage Profile</span></a>
              </li>
              <li>
                <a href="{{route('manage_address')}}" class="nav-link px-0 align-middle text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Address"><i class="bi bi-house-door-fill text-dark"></i> <span class="ms-1 d-none d-sm-inline text-dark fw-bold">Manage Address</span></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col py-3">
          <span class="text-danger">
            @if(Session::has('last_login'))
            Previous login: {{ Session::get('last_login') }}
            @endif
          </span>
          <h1 class="text-center">Manage Profile</h1>
          <div class="container">
            <fieldset>
              <legend>{{--Image fetch from db and set here by ajax success response and this image is just use for testing/checking purpose--}}
                <img id="legendImage" src="{{ asset('Business_Logo/logo1.png') }}" alt="Manage User Profile" style="width: 90px; height: 90px;">
              </legend>
              <form id="myform" autocomplete="off">
                <div class="row g-2 mt-2 mb-2">
                  <div class="col-md">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}">
                      <label for="name">Name</label>
                      <span class="profile_update_error" id="name_error" style="color: red;"></span>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-floating">
                      <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}">
                      <label for="email">Email address</label>
                      <span class="profile_update_error" id="email_error" style="color: red;"></span>
                    </div>
                  </div>
                </div>
                <div class="row g-2 mt-2 mb-2">
                  <div class="col-md">
                    <div class="form-floating">
                      <input type="number" class="form-control" id="number" name="number" value="{{old('number')}}">
                      <label for="number">Mobile</label>
                      <span class="profile_update_error" id="number_error" style="color: red;"></span>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-floating">
                      <input type="file" class="form-control" id="profile" name="profile" value="{{old('profile')}}">
                      <label for="profile">Profile Picture</label>
                      <span class="profile_update_error" id="profile_error" style="color: red;"></span>
                    </div>
                  </div>
                </div>
                <div class="row g-2 mt-2 mb-2">
                  <div class="col-md">
                    <div class="form-floating">
                      <input type="password" class="form-control" id="password" name="password" value="{{old('password')}}">
                      <label for="password">Password</label>
                      <span class="profile_update_error" id="password_error" style="color: red;"></span>
                    </div>
                  </div>
                </div>
                <div class="row g-2 mt-4 mb-4">
                  <div class="col text-center">
                    <button type="submit" id="submit" class="btn btn-warning">Update</button>
                  </div>
                </div>
              </form>
              {{-- OTP Verification Form (Initially hidden) --}}
              <form id="otp_form" class="otp-form-container" autocomplete="off">
                <div class="form-outline mb-4 mt-4">
                  <label class="form-label" for="otp">Enter OTP</label>
                  <input type="text" id="otp" name="otp" class="form-control form-control-lg"
                    placeholder="Enter OTP" required />
                </div>
                <div class="text-center mb-4">
                  <button type="submit" id="submit_otp" class="btn btn-warning">Verify & Update</button>
                </div>
              </form>

            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{--Footer--}}
  <x-footer />

  <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
  <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    $(document).ready(function() {

      //fetch form details to filled
      $.ajax({
        url: '{{ route("user_profile_fetch") }}',
        type: 'GET',
        success: function(response) {
          var fetch = response.data;
          $('#name').val(fetch.name);
          $('#email').val(fetch.email);
          $('#number').val(fetch.phone_number);
          //$('#password').val(fetch.password); Dont show password data in view
          // var imageUrl = fetch.profile_pic;  
          // var imageUrl = "{{ asset('storage/user_profile/') }}" + '/' + fetch.profile_pic;
          // $('#legendImage').attr('src', imageUrl);
          // Check if profile_pic exists in the database
          var imageUrl = fetch.profile_pic ?
            "{{ asset('storage/user_profile/') }}" + '/' + fetch.profile_pic :
            "{{ asset('Business_Logo/logo1.png') }}"; // Default image path
          // Update the image if profile_pic exists
          $('#legendImage').attr('src', imageUrl);
        }
      });

      //update form details
      $('#submit').click(function(e) {
        e.preventDefault();
        $('#submit').prop('disabled', true); // Disable the button
        $('.spinner-container').show(); //show spinner
        var formData = new FormData($('#myform')[0]); // Target the form directly
        $.ajax({
          url: "{{route('user_profile_update')}}",
          type: 'POST',
          data: formData,
          contentType: false, //multipart/form-data use
          processData: false, //by default true we use because normally we send data in form of array ,object but in here we pass data in form of file also
          headers: { //for csf token also add meta tag at head tag
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.status === 'validateerror') {
              $('.profile_update_error').text(''); // Clear any previous error messages
              var errors = response.message;
              $.each(errors, function(key, value) {
                $('#' + key + '_error').text(value[0]); // Update error message next to the field  //0 optional if miltille error inside same field then 0,1 etc used else for single error inside same field not required
              });
              $('.spinner-container').hide(); //hide the spinner
              $('#submit').prop('disabled', false); //Re-enable the button
            } else if (response.status === 'otp_success') {
              //alert(response.message);
              toastr.success(response.message, "", {
                "toastClass": "toastr-success"
              });
              $('.spinner-container').hide(); //hide the spinner
              $('#submit').prop('disabled', false); //Re-enable the button
              // Hide the update form and show the OTP form
              $('#myform').hide();
              $('#otp_form').show();
            } else if (response.status === 'success') {
              //alert(response.message);
              toastr.success(response.message, "", {
                "toastClass": "toastr-success"
              });
              $('.spinner-container').hide(); //hide the spinner
              $('#submit').prop('disabled', false); //Re-enable the button
            } else if (response.status === 'error') {
              toastr.error(response.message, "", {
                "toastClass": "toastr-error"
              });
              $('.spinner-container').hide(); //hide the spinner
              $('#submit').prop('disabled', false); //Re-enable the button
            }
          }
        });
      });

      //verify otp for email update only
      $('#submit_otp').click(function(e) {
        e.preventDefault();
        $('#submit_otp').prop('disabled', true); // Disable the button
        $('.spinner-container').show(); //show spinner
        var otp = $('#otp').val();
        // Make an AJAX call to verify the OTP here
        $.ajax({
          url: "{{route('user_profile_update_email_verify')}}",
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
              $('.spinner-container').hide(); //hide the spinner
              $('#submit_otp').prop('disabled', false); //Re-enable the button
              // Hide the otp form and show the update form
              $('#otp_form').hide();
              $('#myform').show();
              $('#otp').val(''); //reset otp filled
            } else {
              $('.spinner-container').hide(); //hide the spinner
              $('#submit_otp').prop('disabled', false); //Re-enable the button
              $('#otp').val(''); //reset otp filled
              toastr.error(response.message, "", {
                "toastClass": "toastr-error"
              });
            }
          }
        });
      });

    });
  </script>
</body>

</html>