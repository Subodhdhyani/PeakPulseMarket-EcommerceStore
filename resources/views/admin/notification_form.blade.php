<x-dashboard_common>
    <x-slot:dynamic_title_top>
        Add Notification - PeakPulseMarket
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

                .form-label {
                    font-weight: bold;
                }

                .full-width-btn {
                    width: 100%;
                }

                .submit_error {
                    color: red;
                    font-size: 0.875rem;
                }
            </style>

            <h1 class="text-center text-danger">Manage Notification for all User</h1>
            <div class="mt-2 mb-2" style="text-align: right;">
                <a href="{{route('manage_notification')}}">
                    <button type="button" class="btn btn-warning">
                        Manage Notification
                    </button>
                </a>
            </div>

            <div class="container">
                <form id="submit_notification_form" autocomplete="off">
                    <fieldset>
                        <legend>Add Notification Content</legend>

                        <!-- Title -->
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <label for="title" class="form-label">Title:</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" required>
                                <span class="submit_error" id="title_error"></span>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <label for="message" class="form-label">Message:</label>
                                <input type="text" class="form-control" name="message" id="message" value="{{ old('message') }}" required>
                                <span class="submit_error" id="message_error"></span>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <label for="image" class="form-label">Image:</label>
                                <input type="file" class="form-control" name="image" id="image">
                                <span class="submit_error" id="image_error"></span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <input type="submit" id="submit" class="btn btn-warning full-width-btn" value="Send Notification">
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>

            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when form load--}}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

            <script>
                $(document).ready(function() {
                    $('#submit_notification_form').submit(function(e) {
                        e.preventDefault();
                        var formData = new FormData(this);
                        $('#submit').prop('disabled', true).val('Sending...');; //disable button to protect multiple lick
                        $('.spinner-container').show(); //show spinner

                        $.ajax({
                            url: "{{ route('notification_store') }}",
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $('.submit_error').text('');

                                if (response.status === 'validateerror') {
                                    // Handle validation errors
                                    $.each(response.message, function(key, value) {
                                        $('#' + key + '_error').text(value[0]);
                                    });
                                } else if (response.status === 'success') {
                                    // Show success message
                                    toastr.success(response.message, "", {
                                        "toastClass": "toastr-success"
                                    });

                                    // Reset the form
                                    $('#submit_notification_form')[0].reset();
                                } else if (response.status === 'error') {
                                    // Show error message
                                    toastr.error(response.message, "", {
                                        "toastClass": "toastr-error"
                                    });
                                }
                            },
                            error: function() {
                                // In case of any AJAX error, enable the submit button again
                                $('#submit').prop('disabled', false);
                                toastr.error('An error occurred while submitting the form.');
                                $('.spinner-container').hide(); //hide the spinner
                            },
                            complete: function() {
                                // Always enable the submit button again when done
                                $('#submit').prop('disabled', false).val('Send Notification');
                                $('.spinner-container').hide(); //hide the spinner
                            }
                        });
                    });
                });
            </script>


            </x-slot>
</x-dashboard_common>