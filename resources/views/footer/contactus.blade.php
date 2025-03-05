<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Contact Us - PeakPulseMarket</title>
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
      padding: 20px;
      background-color: #fff;
      /* Set background color */
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      /* Add a subtle box shadow */
      height: auto;
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


    <div class="container mt-4 mb-4">
      <img src="{{asset('contactus/contactus-banner.png')}}" alt="contactus banner" style="height:auto; width:100%;">
      <div class="row mt-2">
        <div class="col-lg-8 col-md-12 mb-4">
          <div class="custom-div">
            <!-- Content for the first div -->
            <h6 class="text-warning">Get in touch</h6>
            <h4>Write Us A Message</h4>
            <form id="contact_form" autocomplete="off">
              <div class="row g-2">
                <div class="col-md">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" required>
                    <label for="name">Your Name</label>
                    <span class="submit_error" id="name_error"></span>
                  </div>
                </div>
                <div class="col-md">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="subject" name="subject" value="{{old('subject')}}" required>
                    <label for="subject">Your Subject</label>
                    <span class="submit_error" id="subject_error"></span>
                  </div>
                </div>
              </div>

              <div class="row g-2 mt-2">
                <div class="col-md">
                  <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" required>
                    <label for="email">Your Email</label>
                    <span class="submit_error" id="email_error"></span>
                  </div>
                </div>
                <div class="col-md">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="number" name="number" value="{{old('number')}}" required>
                    <label for="number">Your Phone</label>
                    <span class="submit_error" id="number_error"></span>
                  </div>
                </div>
              </div>
              <div class="row g-2 mt-2">
                <div class="form-floating">
                  <textarea class="form-control" id="comment" name="comment" style="height: 150px" required>{{ old('comment') }}</textarea>
                  <label for="comment">Your Message</label>
                  <span class="submit_error" id="comment_error"></span>
                </div>
              </div>
              <div class="col-12 mt-2">
                <button type="submit" id="submit" class="btn btn-warning">SEND MESSAGE</button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-lg-4 col-md-12">
          <div class="custom-div text-center">
            <!-- Content for the second div -->
            <div class="col-12">
              <i class="fa-solid fa-location-dot fa-2xl mb-3" style="color: #FFD43B;"></i>
              <h5>Our Address:</h5>
              <p class="mb-4">
                Peak Pulse Internet Private Limited,
                Himalayan Tower, IT Park,<br>
                Sahastradhara Rd, Dehradun,<br>
                Uttarakhand, India
              </p>
            </div>
            <div class="col-12">
              <i class="fa-solid fa-phone fa-2xl mb-3" style="color: #FFD43B;"></i>
              <h5>Call us Now:</h5>
              <p class="mb-4" id="fetch_phone">9999999999</p>
            </div>
            <div class="col-12">
              <i class="fa-solid fa-envelope fa-2xl mb-3" style="color: #FFD43B;"></i>
              <h5>Email:</h5>
              <p class="mb-4" id="fetch_email">info@peakpulsemarket.com</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <x-footer />
  <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
  <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when form/paage load--}}
  <script>
    $(document).ready(function() {
      //For Contact and email Fetch from db and append to page
      $.ajax({
        url: '{{ route("footer_fetch") }}',
        type: 'GET',
        success: function(response) {
          if (response.status === 'success') {
            var email = response.email;
            var mobile = response.mobile;
            // Update email and mobile in the page
            $('#fetch_email').text(email);
            $('#fetch_phone').text(mobile);
          }
        }
      });


      //Send form data
      $('#contact_form').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#submit').prop('disabled', true).val('SENDING.....'); //disable button to protect multiple click
        $('.spinner-container').show(); //show spinner

        $.ajax({
          url: "{{ route('contactform') }}",
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
              $('#contact_form')[0].reset();
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
            $('#submit').prop('disabled', false).val('SEND MESSAGE');
            $('#ajax-message')
              .addClass('alert alert-danger')
              .text('An error occurred while submitting the form.')
              .fadeIn();
            $('.spinner-container').hide(); //hide the spinner
          },
          complete: function() {
            // Always enable the submit button again when done
            $('#submit').prop('disabled', false).val('SEND MESSAGE');
            $('.spinner-container').hide(); //hide the spinner
          }
        });
      });



    });
  </script>
</body>

</html>