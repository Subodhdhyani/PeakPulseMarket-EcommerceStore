<x-dashboard_common>
  <x-slot:dynamic_title_top>
    Manage Admin Profile - PeakPulseMarket
    </x-slot>
    <x-slot:section_dynamic_content>
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
      </style>
      <h1 class="text-center text-danger">Manage Admin Profile</h1>
      <div class="container">
        <fieldset>
          <legend>{{--Image fetch from db and set here by ajax success response and this image is just use for testing/checking purpose--}}
            <img id="legendImage" src="{{ asset('Business_Logo/logo1.png') }}" alt="Update Admin Profile" style="width: 90px; height: 90px;">
          </legend>

          <form id="form" autocomplete="off">
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
                  <input type="text" class="form-control" id="address" name="address" value="{{old('address')}}">
                  <label for="address">Address</label>
                  <span class="profile_update_error" id="address_error" style="color: red;"></span>
                </div>
              </div>
              <div class="col-md">
                <div class="form-floating">
                  <input type="password" class="form-control" id="password" name="password" value="{{old('password')}}">
                  <label for="password">Password</label>
                  <span class="profile_update_error" id="password_error" style="color: red;"></span>
                </div>
              </div>
            </div>
            <div class="row g-2 mt-4 mb-4">
              <div class="col">
                <input type="submit" id="submit" class="form-control btn btn-warning" value="Update">
              </div>
            </div>
          </form>
        </fieldset>
      </div>
      <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script>
        $(document).ready(function() {
          $('.spinner-container').show(); //show spinner
          $.ajax({
            url: '{{ route("profileupdatereq") }}',
            type: 'GET',
            success: function(response) {
              var fetch = response.data;
              $('#name').val(fetch.name);
              $('#email').val(fetch.email);
              $('#number').val(fetch.phone_number);
              $('#address').val(fetch.address);
              //$('#password').val(fetch.password); Dont show password data in view

              // var imageUrl = fetch.profile_pic;
              // var imageUrl = "{{ asset('storage/admin_profile/') }}" + '/' + fetch.profile_pic;
              //$('#legendImage').attr('src', imageUrl);
              // Check if fetch.profile_pic exists and is not empty
              if (fetch.profile_pic) {
                var imageUrl = "{{ asset('storage/admin_profile/') }}" + '/' + fetch.profile_pic;
                $('#legendImage').attr('src', imageUrl);
              } else {
                // Optionally, set a default image used at top
                $('#legendImage').attr('src', "{{ asset('Business_Logo/logo1.png') }}");
              }

            },
            complete: function() {
              $('.spinner-container').hide(); // Hide spinner after data is loaded
            },
            error: function() {
              $('.spinner-container').hide(); // Hide spinner after any error
            }
          });

          $('#submit').click(function(e) {
            e.preventDefault();
            $('#submit').prop('disabled', true); // Disable the button
            var formData = new FormData($('#form')[0]); // Target the form directly
            $('.spinner-container').show(); //show spinner
            $.ajax({
              url: "{{route('profileupdatepost')}}",
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
                  $('#submit').prop('disabled', false); //Re-enable the button
                  $('.spinner-container').hide(); //hide the spinner
                } else if (response.status === 'success') {
                  //alert(response.message);
                  toastr.success(response.message, "", {
                    "toastClass": "toastr-success"
                  });
                  $('#submit').prop('disabled', false); //Re-enable the button
                  $('.spinner-container').hide(); //hide the spinner
                }

              }

            });
          });


        });
      </script>


      </x-slot>
</x-dashboard_common>