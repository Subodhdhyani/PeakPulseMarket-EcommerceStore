<x-dashboard_common>
  <x-slot:dynamic_title_top>
    FrontEnd Content - PeakPulseMarket
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
      <h1 class="text-center text-danger">Manage FrontEnd Dynamic Content</h1>
      <div class="container">
        <form id="submit_carousel_form" autocomplete="off">
          <fieldset>
            <legend>Manage Carousel Image</legend>
            <div class="row mt-4 mb-4">
              <div class="col">
                <input type="file" class="form-control" name="firstpic" id="firstpic" value="{{old('firstpic')}}">
                <span class="submit_error" id="firstpic_error" style="color: red;"></span>
              </div>
              <div class="col">
                <div id="firstpic_show" style="background-color: red; height:auto; width:50%;"></div>
              </div>
            </div>
            <div class="row mt-4 mb-4">
              <div class="col">
                <input type="file" class="form-control" name="secondpic" id="secondpic" value="{{old('secondpic')}}">
                <span class="submit_error" id="secondpic_error" style="color: red;"></span>
              </div>
              <div class="col">
                <div id="secondpic_show" style="background-color: red; height:auto; width:50%;"></div>
              </div>
            </div>
            <div class="row mt-4 mb-4">
              <div class="col">
                <input type="file" class="form-control" name="thirdpic" id="thirdpic" value="{{old('thirdpic')}}">
                <span class="submit_error" id="thirdpic_error" style="color: red;"></span>
              </div>
              <div class="col">
                <div id="thirdpic_show" style="background-color: red; height:auto; width:50%;"></div>
              </div>
            </div>
            <div class="row mt-4 mb-4">
              <div class="col">
                <input type="submit" id="submit_carousel" class="form-control btn btn-warning" value="Update" data-action="carousel">
              </div>
            </div>
          </fieldset>
        </form>
        {{--Other Form--}}
        <form id="contact_form" autocomplete="off">
          <fieldset>
            <legend>Manage Contact Detail</legend>
            <form id="submit_contact_form" autocomplete="off">
              <div class="row g-2">
                <div class="col-md">
                  <div class="form-floating">
                    <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}">
                    <label for="email">Email address</label>
                    <span class="submit_error" id="email_error" style="color: red;"></span>
                  </div>
                </div>
                <div class="col-md">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="number" name="number" value="{{old('number')}}">
                    <label for="number">Mobile Number</label>
                    <span class="submit_error" id="number_error" style="color: red;"></span>
                  </div>
                </div>
              </div>
              <div class="row mt-4 mb-4">
                <div class="col">
                  <input type="submit" id="submit_contact" class="form-control btn btn-warning" value="Update" data-action="contact">
                </div>
              </div>

          </fieldset>
        </form>

      </div>
      <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script>
        $(document).ready(function() {
          $('.spinner-container').show();
          $.ajax({
            url: '{{ route("frontcontentreq") }}',
            type: 'GET',
            success: function(response) {
              var fetch = response.data;
              $('#email').val(fetch.email);
              $('#number').val(fetch.number);
              $('#firstpic_show').html('<img src="{{ asset("storage/front_content/") }}/' + fetch.firstpic + '" style="width: 100%; height: 100%;" />');
              $('#secondpic_show').html('<img src="{{ asset("storage/front_content/") }}/' + fetch.secondpic + '" style="width: 100%; height: 100%;" />');
              $('#thirdpic_show').html('<img src="{{ asset("storage/front_content/") }}/' + fetch.thirdpic + '" style="width: 100%; height: 100%;" />');
            },
            complete: function() {
              $('.spinner-container').hide(); // Hide spinner after data is loaded
            }

          });
          /*
          In here two click button on different submit and add input type hidden value=carousel/contact and this pass to backend and then by this apply validation and store record
           $('#submit_carousel').click(function(e){
              e.preventDefault();
              var formData = new FormData($('#submit_carousel_form')[0]); // Target the form directly
                  $.ajax({
                              url: "{{route('frontcontentpost')}}", 
                              type: 'POST',
                              data: formData,
                              contentType: false, //multipart/form-data use
                              processData: false, //by default true we use because normally we send data in form of array ,object but in here we pass data in form of file also
                              headers: {        //for csf token also add meta tag at head tag
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                              },
                              success: function(response){
                                  if(response.status ==='validateerror'){
                                      $('.submit_carousel_error').text(''); // Clear any previous error messages
                                      var errors = response.message;
                                      $.each(errors, function(key, value) {
                                      $('#'+key+'_error').text(value[0]); // Update error message next to the field  //0 optional if miltille error inside same field then 0,1 etc used else for single error inside same field not required
                                      });
                                  } else if (response.status === 'success'){
                                      //alert(response.message);
                                      toastr.success(response.message, "", {
                                          "toastClass": "toastr-success"
                                                                   });
                                  }

                              }

                  });
          });
            
          $('#submit_contact').click(function(e){
              e.preventDefault();
              var formData = new FormData($('#submit_contact_form')[0]); // Target the form directly
                  $.ajax({
                              url: "{{route('frontcontentpost')}}", 
                              type: 'POST',
                              data: formData,
                              contentType: false, //multipart/form-data use
                              processData: false, //by default true we use because normally we send data in form of array ,object but in here we pass data in form of file also
                              headers: {        //for csf token also add meta tag at head tag
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                              },
                              success: function(response){
                                  if(response.status ==='validateerror'){
                                      $('.submit_contact_error').text(''); // Clear any previous error messages
                                      var errors = response.message;
                                      $.each(errors, function(key, value) {
                                      $('#'+key+'_error').text(value[0]); // Update error message next to the field  //0 optional if miltille error inside same field then 0,1 etc used else for single error inside same field not required
                                      });
                                  } else if (response.status === 'success'){
                                      //alert(response.message);
                                      toastr.success(response.message, "", {
                                          "toastClass": "toastr-success"
                                                                   });
                                  }

                              }

                  });
          });
          */

          //Now use submit by single button two different form by single button identified by data-action on submit button
          $('form').submit(function(e) { //target all the form in page
            e.preventDefault();
            $('#submit_contact').prop('disabled', true); // Disable the button
            $('#submit_carousel').prop('disabled', true); // Disable the button
            $('.spinner-container').show(); //show spinner
            // Get the action from data attribute ADDdddddddddd    data-action="value" to both submit button
            var action = $(this).find(':submit').data('action');
            var formData = new FormData(this);
            //Append the action to form data
            formData.append('button_clicked', action);

            $.ajax({
              url: "{{route('frontcontentpost')}}",
              type: 'POST',
              data: formData,
              contentType: false, //multipart/form-data use
              processData: false, //by default true we use because normally we send data in form of array ,object but in here we pass data in form of file also
              headers: { //for csf token also add meta tag at head tag
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
                if (response.status === 'validateerror') {
                  $('.submit_error').text(''); // Clear any previous error messages
                  var errors = response.message;
                  $.each(errors, function(key, value) {
                    $('#' + key + '_error').text(value[0]); // Update error message next to the field  //0 optional if miltille error inside same field then 0,1 etc used else for single error inside same field not required
                  });
                  $('.spinner-container').hide(); //hide the spinner
                  $('#submit_contact').prop('disabled', false); //Re-enable the button
                  $('#submit_carousel').prop('disabled', false); //Re-enable the button
                } else if (response.status === 'success') {
                  //alert(response.message);
                  toastr.success(response.message, "", {
                    "toastClass": "toastr-success"
                  });
                  $('.spinner-container').hide(); //hide the spinner
                  $('#submit_contact').prop('disabled', false); //Re-enable the button
                  $('#submit_carousel').prop('disabled', false); //Re-enable the button
                }
              }
            });



          });

        });
      </script>

      </x-slot>
</x-dashboard_common>