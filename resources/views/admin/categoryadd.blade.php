<x-dashboard_common>

    <x-slot:dynamic_title_top>
        Category - PeakPulseMarket
        </x-slot>
        <x-slot:section_dynamic_content>
            <style>
                .image-zoom {
                    transition: transform 0.3s ease;
                }

                .image-zoom:hover {
                    transform: scale(4.2);
                    /* Adjust the scale factor to control the zoom level */
                }

                .toastr-success {
                    background-color: green !important;
                }

                .toastr-error {
                    background-color: red !important;
                }
            </style>

            <div class="container">
                {{--Heading--}}
                <h1 class="text-center text-danger">Manage and Add Category</h1>
                {{--Model Button for add new category--}}
                <div class="mt-2 mb-2" style="text-align: right;">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#targetmodal">
                        Add New Category
                    </button>
                </div>

                {{--Table to show Category Record--}}
                <div>
                    <h2 class="text-center">Manage Category</h2>
                    <table class="table  table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="d-none d-sm-table-cell">Id</th>
                                <th scope="col">Category Name</th>
                                <th scope="col" class="d-none d-sm-table-cell">Category Image</th>
                                <th scope="col">Operation</th>
                            </tr>
                        </thead>
                        <tbody id="fetch_category">
                            <!-- Table body content goes here -->
                        </tbody>
                    </table>
                </div>
            </div>

            {{--Modal Add Category--}}
            <div class="modal fade" id="targetmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:antiquewhite">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Category</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form autocomplete="off" id="myform">
                                <div class="mb-3">
                                    <label for="category_name" class="col-form-label">Category Name</label>
                                    <input type="text" class="form-control" id="category_name" name="category_name" value="{{old('category_name')}}" required>
                                    <span class="add_category_error" id="category_name_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="category_image" class="col-form-label">Category Image</label>
                                    <input type="file" class="form-control" id="category_image" name="category_image" value="{{old('category_image')}}" required>
                                    <span class="add_category_error" id="category_image_error" style="color: red;"></span>
                                </div>
                                <hr>
                                <button type="button" id="add_category" class="btn btn-warning d-block mx-auto">Add Category</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            {{--Modal Update Category--}}
            <div class="modal fade" id="targetmodal_update" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:antiquewhite">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Category</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form autocomplete="off" id="myform_update">
                                <div class="mb-3">

                                    <input type="hidden" id="hidden_id" name="hidden_id" value="">{{--For Find Record from db/Primary Key--}}
                                    <label for="category_name_update" class="col-form-label">Category Name</label>
                                    <input type="text" class="form-control" id="category_name_update" name="category_name_update" value="{{old('category_name_update')}}">
                                    <span class="add_category_update_error" id="category_name_update_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="category_image_update" class="col-form-label">Category Image</label>
                                    <input type="file" class="form-control" id="category_image_update" name="category_image_update" value="{{old('category_image_update')}}">
                                    <div class="mt-2" id="category_image_update_show" style="height: 80px; width: 200px;"></div>
                                    <span class="add_category_update_error" id="category_image_update_error" style="color: red;"></span>
                                </div>
                                <hr>
                                <button type="button" id="add_category_update" class="btn btn-warning d-block mx-auto">Update Category</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{--Add jquery and Sweet Alert and toastr--}}
            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                $(document).ready(function() {
                    //Add Category Send/Store inside db and display message(success/error)
                    $('#add_category').click(function(e) {
                        e.preventDefault();
                        $('#add_category').prop('disabled', true); // Disable the button
                        $('.spinner-container').show(); //show spinner


                        //Here not manual and serilaize for collect data
                        /* var formData = new FormData();
                        formData.append('category_name', $('#category_name').val());
                        formData.append('category_image', $('#category_image')[0].files[0]);*/
                        var formData = new FormData($('#myform')[0]); // Target the form directly
                        $.ajax({
                            url: "{{route('categoryreq')}}",
                            type: 'POST',
                            data: formData,
                            contentType: false, //multipart/form-data use
                            processData: false, //by default true we use because normally we send data in form of array ,object but in here we pass data in form of file also
                            headers: { //for csf token also add meta tag at head tag
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    $('#myform').trigger('reset');
                                    $('#add_category').prop('disabled', false); // Enable the button
                                    $('.spinner-container').hide(); //hide the spinner
                                    swal("Add Category Status", response.message, "success");
                                    //loaddata();
                                    setTimeout(() => {
                                        loaddata();
                                    }, 5000);
                                    //alert(response.message);
                                } else if (response.status === 'validateerror') {
                                    $('.add_category_error').text(''); // Clear any previous error messages
                                    var errors = response.message;
                                    $.each(errors, function(key, value) {
                                        $('#' + key + '_error').text(value[0]); // Update error message next to the field  //0 optional if miltille error inside same field then 0,1 etc used else for single error inside same field not required
                                    });
                                    $('#add_category').prop('disabled', false); // Enable the button
                                    $('.spinner-container').hide(); //hide the spinner
                                }

                            },
                        });

                    });
                    //Display the Category or Fetch record from db
                    function loaddata() {
                        $('.spinner-container').show();
                        $.ajax({
                            url: "{{route('categorydisplay')}}",
                            type: 'GET',
                            success: function(response) {
                                var respo = response.data;
                                $('#fetch_category').empty();
                                if (respo.length === 0) {
                                    $('#fetch_category').append('<tr><td colspan="4" class ="text-center text-danger" style="font-size: 30px;">No Category Found</td></tr>');
                                } else {
                                    respo.forEach(function(record) {
                                        var imageUrl = "{{ asset('storage/category_image/') }}" + '/' + record.category_image;
                                        $('#fetch_category').append(
                                            '<tr>' +
                                            '<td class="d-none d-sm-table-cell">' + record.id + '</td>' +
                                            '<td>' + record.category_name + '</td>' +
                                            //'<td>' + record.category_image + '</td>' +

                                            '<td class="d-none d-sm-table-cell">' + '<img src="' + imageUrl + '" alt="load" class="image-zoom" style="width: 50px; height: 50px; border-radius: 50%;">' + '</td>' + // Set width to 100px, height auto, and border-radius to 50%
                                            '<td>' +
                                            '<a href="" class="update-record text-dark" data-bs-toggle="modal" data-bs-target="#targetmodal_update" data-id="' + record.id + '" data-bs-toggle="tooltip" title="Update"><i class="bi bi-pencil"></i></a>&emsp;' +
                                            '<a href="" class="delete-record text-dark" data-id="' + record.id + '" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-archive"></i></a>' +
                                            '</td>' +
                                            '</tr>'
                                        )
                                    });



                                    //Delete Ajax fetch the id from anchor tag on click
                                    $('.delete-record').click(function(e) {
                                        e.preventDefault();
                                        $('.spinner-container').show(); //show spinner
                                        //var button = $(this); // Store reference to the clicked button
                                        //var recordId = button.data('id');
                                        var recordId = $(this).data('id');

                                        // SweetAlert confirmation
                                        swal({
                                            title: "Are you sure?",
                                            text: "You won't be able to recover this category!",
                                            icon: "warning",
                                            buttons: true,
                                            dangerMode: true,
                                        }).then((willDelete) => {
                                            if (willDelete) {
                                                //deletion via AJAX
                                                $.ajax({
                                                    url: '/admin/categorydelete/' + recordId,
                                                    type: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                                    },
                                                    success: function(response) {
                                                        if (response.status === 'success') {
                                                            toastr.success(response.message, "", {
                                                                "toastClass": "toastr-success"
                                                            });
                                                            $('.spinner-container').hide(); //hide the spinner
                                                            $(this).closest('tr').hide();
                                                        } else if (response.status === 'error') {
                                                            toastr.error(response.message, "", {
                                                                "toastClass": "toastr-error"
                                                            });
                                                            $('.spinner-container').hide(); //hide the spinner
                                                        }
                                                    }.bind(this) //remove bind when use other closet way
                                                });
                                            } else {
                                                swal("Cancelled", "Deletion Cancelled !", "info");
                                                $('.spinner-container').hide(); //hide the spinner
                                            }
                                        });
                                    });


                                    //Update Ajax fetch the id from anchor tag on click and open model and fetch data inside it
                                    $('.update-record').click(function(e) {

                                        e.preventDefault();
                                        var recordId = $(this).data('id');
                                        $.ajax({
                                            url: '/admin/categoryupdate/' + recordId,
                                            type: 'GET',
                                            success: function(response) {
                                                var fetch = response.data;
                                                var imageUrl = "{{ asset('storage/category_image/') }}" + '/' + fetch.category_image;
                                                $('#hidden_id').val(fetch.id);
                                                $('#category_name_update').val(fetch.category_name);
                                                $('#category_image_update_show').html('<img src="' + imageUrl + '" style="max-width: 100%; max-height: 100%;" />');
                                                //For security reason we doesnt put image value inside input file
                                            }

                                        });

                                    });




                                }

                            },
                            complete: function() {
                                $('.spinner-container').hide(); // Hide spinner after data is loaded
                            },
                            error: function() {
                                $('.spinner-container').hide(); // Hide spinner in case of error
                            }
                        });

                    }
                    loaddata();


                    //Update the category data inside modal
                    $('#add_category_update').click(function(e) {
                        e.preventDefault();
                        $('.spinner-container').show(); //show spinner
                        $('#add_category_update').prop('disabled', true); // Disable the button
                        var formData = new FormData($('#myform_update')[0]); // Target the form directly
                        $.ajax({
                            url: "{{route('categoryupdatestore')}}",
                            type: 'POST',
                            data: formData,
                            contentType: false, //multipart/form-data use
                            processData: false, //by default true we use because normally we send data in form of array ,object but in here we pass data in form of file also
                            headers: { //for csf token also add meta tag at head tag
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    $('#add_category_update').prop('disabled', false); //Enable the button
                                    $('.spinner-container').hide(); //hide the spinner
                                    //$('#myform_update').trigger('reset');
                                    swal("Update Category Status", response.message, "success");
                                    //loaddata();
                                    setTimeout(() => {
                                        loaddata();
                                    }, 5000);
                                    //alert(response.message);
                                } else if (response.status === 'validateerror') {
                                    $('.add_category_update_error').text(''); // Clear any previous error messages
                                    var errors = response.message;
                                    $.each(errors, function(key, value) {
                                        $('#' + key + '_error').text(value[0]); // Update error message next to the field  //0 optional if miltille error inside same field then 0,1 etc used else for single error inside same field not required
                                    });
                                }
                                $('#add_category_update').prop('disabled', false); //Enable the button
                                $('.spinner-container').hide(); //hide the spinner
                            }
                        });
                    });




                });
            </script>


            </x-slot>

</x-dashboard_common>