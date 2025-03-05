<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Address - PeakPulseMarket</title>
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
          <h1 class="text-center">Manage Address</h1>
          <fieldset>
            <legend>Saved Address</legend>
            <h5 class="text-center text-success" id="address_display"></h5>
          </fieldset>

          <div class="container">
            <fieldset>
              <legend>Update Address</legend>
              <form id="form" autocomplete="off">
                <div class="row g-2 mt-2 mb-2">
                  <div class="col-md">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="locality" name="locality" value="{{old('locality')}}" required>
                      <label for="locality">Locality</label>
                      <span class="address_update_error" id="locality_error" style="color: red;"></span>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="city" name="city" value="{{old('city')}}" required>
                      <label for="city">City</label>
                      <span class="address_update_error" id="city_error" style="color: red;"></span>
                    </div>
                  </div>
                </div>

                <div class="row g-2 mt-2 mb-2">
                  <div class="col-md">
                    <div class="form-floating">
                      <input type="number" class="form-control" id="pin" name="pin" value="{{old('pin')}}" required>
                      <label for="pin">Pin Code</label>
                      <span class="address_update_error" id="pin_error" style="color: red;"></span>
                    </div>
                  </div>

                  <div class="col-md">
                    <div class="form-floating">
                      <select class="form-select" id="state" name="state" required>
                        <option value="">Select State</option>
                        <option value="Andhra Pradesh" {{ old('state') == 'Andhra Pradesh' ? 'selected' : '' }}>Andhra Pradesh</option>
                        <option value="Arunachal Pradesh" {{ old('state') == 'Arunachal Pradesh' ? 'selected' : '' }}>Arunachal Pradesh</option>
                        <option value="Assam" {{ old('state') == 'Assam' ? 'selected' : '' }}>Assam</option>
                        <option value="Bihar" {{ old('state') == 'Bihar' ? 'selected' : '' }}>Bihar</option>
                        <option value="Chhattisgarh" {{ old('state') == 'Chhattisgarh' ? 'selected' : '' }}>Chhattisgarh</option>
                        <option value="Goa" {{ old('state') == 'Goa' ? 'selected' : '' }}>Goa</option>
                        <option value="Gujarat" {{ old('state') == 'Gujarat' ? 'selected' : '' }}>Gujarat</option>
                        <option value="Haryana" {{ old('state') == 'Haryana' ? 'selected' : '' }}>Haryana</option>
                        <option value="Himachal Pradesh" {{ old('state') == 'Himachal Pradesh' ? 'selected' : '' }}>Himachal Pradesh</option>
                        <option value="Jharkhand" {{ old('state') == 'Jharkhand' ? 'selected' : '' }}>Jharkhand</option>
                        <option value="Karnataka" {{ old('state') == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                        <option value="Kerala" {{ old('state') == 'Kerala' ? 'selected' : '' }}>Kerala</option>
                        <option value="Madhya Pradesh" {{ old('state') == 'Madhya Pradesh' ? 'selected' : '' }}>Madhya Pradesh</option>
                        <option value="Maharashtra" {{ old('state') == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                        <option value="Manipur" {{ old('state') == 'Manipur' ? 'selected' : '' }}>Manipur</option>
                        <option value="Meghalaya" {{ old('state') == 'Meghalaya' ? 'selected' : '' }}>Meghalaya</option>
                        <option value="Mizoram" {{ old('state') == 'Mizoram' ? 'selected' : '' }}>Mizoram</option>
                        <option value="Nagaland" {{ old('state') == 'Nagaland' ? 'selected' : '' }}>Nagaland</option>
                        <option value="Odisha" {{ old('state') == 'Odisha' ? 'selected' : '' }}>Odisha</option>
                        <option value="Punjab" {{ old('state') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                        <option value="Rajasthan" {{ old('state') == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
                        <option value="Sikkim" {{ old('state') == 'Sikkim' ? 'selected' : '' }}>Sikkim</option>
                        <option value="Tamil Nadu" {{ old('state') == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                        <option value="Telangana" {{ old('state') == 'Telangana' ? 'selected' : '' }}>Telangana</option>
                        <option value="Tripura" {{ old('state') == 'Tripura' ? 'selected' : '' }}>Tripura</option>
                        <option value="Uttar Pradesh" {{ old('state') == 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                        <option value="Uttarakhand" {{ old('state') == 'Uttarakhand' ? 'selected' : '' }}>Uttarakhand</option>
                        <option value="West Bengal" {{ old('state') == 'West Bengal' ? 'selected' : '' }}>West Bengal</option>
                        <option value="Andaman and Nicobar Islands" {{ old('state') == 'Andaman and Nicobar Islands' ? 'selected' : '' }}>Andaman and Nicobar Islands</option>
                        <option value="Chandigarh" {{ old('state') == 'Chandigarh' ? 'selected' : '' }}>Chandigarh</option>
                        <option value="Dadra and Nagar Haveli and Daman and Diu" {{ old('state') == 'Dadra and Nagar Haveli and Daman and Diu' ? 'selected' : '' }}>Dadra and Nagar Haveli and Daman and Diu</option>
                        <option value="Lakshadweep" {{ old('state') == 'Lakshadweep' ? 'selected' : '' }}>Lakshadweep</option>
                        <option value="Delhi" {{ old('state') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                        <option value="Puducherry" {{ old('state') == 'Puducherry' ? 'selected' : '' }}>Puducherry</option>
                      </select>
                      <label for="state">State</label>
                      <span class="address_update_error" id="state_error" style="color: red;"></span>
                    </div>
                  </div>

                  <div class="row g-2 mt-4 mb-4">
                    <div class="col text-center">
                      <button type="submit" id="submit" class="btn btn-warning">Update</button>
                    </div>
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
  <script src="{{url('js/spinner.js')}}"></script> {{--Show Spinner during page loading--}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    $(document).ready(function() {
      $.ajax({
        url: '{{ route("user_profile_fetch") }}',
        type: 'GET',
        success: function(response) {
          var fetch = response.data;
          var address_display = `${fetch.name}, ${fetch.address} <br> Phone Number: ${fetch.phone_number}`;
          $('#address_display').html(address_display);
        }
      });

      //Update Address
      $('#submit').click(function(e) {
        e.preventDefault();
        $('#submit').prop('disabled', true); // Disable the button
        $('.spinner-container').show(); //show spinner
        var locality = $('#locality').val();
        var city = $('#city').val();
        var pin = $('#pin').val();
        var state = $('#state').val();

        $.ajax({
          url: "{{route('user_address_update')}}",
          type: 'POST',
          data: {
            locality: locality,
            city: city,
            pin: pin,
            state: state
          },
          headers: { //for csf token also add meta tag at head tag
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.status === 'validateerror') {
              $('.address_update_error').text(''); // Clear any previous error messages
              var errors = response.message;
              $.each(errors, function(key, value) {
                $('#' + key + '_error').text(value[0]); // Update error message next to the field  //0 optional if miltille error inside same field then 0,1 etc used else for single error inside same field not required
              });
              $('#submit').prop('disabled', false); //Re-enable the button
              $('.spinner-container').hide(); //hide the spinner
            } else if (response.status === 'success') {
              //alert(response.message);
              toastr.success(response.message, "", {
                "toastClass": "toastr-success"
              });
              $('#submit').prop('disabled', false); //Re-enable the button
              $('.spinner-container').hide(); //hide the spinner
            } else if (response.status === 'error') {
              toastr.error(response.message, "", {
                "toastClass": "toastr-error"
              });
              $('#submit').prop('disabled', false); //Re-enable the button
              $('.spinner-container').hide(); //hide the spinner
            }
          }
        });
      });
    });
  </script>
</body>

</html>