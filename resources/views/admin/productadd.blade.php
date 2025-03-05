<x-dashboard_common>

    <x-slot:dynamic_title_top>
        Product - PeakPulseMarket
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
                <h1 class="text-center text-danger">Manage and Add Product</h1>
                {{--Model Button for add new category--}}
                <div class="mt-2 mb-2" style="text-align: right;">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#targetmodal">
                        Add New Product
                    </button>
                </div>

                {{--Table to show Category Record--}}
                <div>
                    <h2 class="text-center">Manage Product</h2>
                    <table class="table  table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="d-none d-sm-table-cell">Id</th>
                                <th scope="col" class="d-none d-sm-table-cell">Category Name</th>
                                <th scope="col">Product Name</th>
                                <th scope="col" class="d-none d-sm-table-cell">Stock</th>
                                <th scope="col" class="d-none d-sm-table-cell">Sale Price</th>
                                <th scope="col">Operation</th>
                            </tr>
                        </thead>
                        <tbody id="fetch_product">
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
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Product</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form autocomplete="off" id="myform">
                                <div class="mb-3">
                                    <label for="category_name" class="col-form-label">Category Name</label>
                                    <select class="form-control category_name" id="category_name" name="category_name" required>
                                        {{--<option selected>Select Category</option>--}}
                                    </select>
                                    <span class="add_product_error" id="category_name_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="product_name" class="col-form-label">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" value="{{old('product_name')}}">
                                    <span class="add_product_error" id="product_name_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="product_code" class="col-form-label">Product Code</label>
                                    <input type="text" class="form-control" id="product_code" name="product_code" value="{{old('product_code')}}">
                                    <span class="add_product_error" id="product_code_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="weight" class="col-form-label">Weight (g)</label>
                                    <input type="number" class="form-control" id="weight" name="weight" value="{{old('weight')}}">
                                    <span class="add_product_error" id="weight_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="product_image" class="col-form-label">Product Image</label>
                                    <input type="file" class="form-control" id="product_image" name="product_image" value="{{old('product_image')}}">
                                    <span class="add_product_error" id="product_image_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="col-form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" value="{{old('quantity')}}">
                                    <span class="add_product_error" id="quantity_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="original_price" class="col-form-label">Original Price</label>
                                    <input type="number" class="form-control" id="original_price" name="original_price" value="{{old('original_price')}}">
                                    <span class="add_product_error" id="original_price_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="discount" class="col-form-label">Discount in Percentage (%)</label>
                                    <input type="number" class="form-control" id="discount" name="discount" value="{{old('discount')}}">
                                    <span class="add_product_error" id="discount_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="col-form-label">Description</label>
                                    <textarea id="description" class="form-control" name="description">{{old('description')}}</textarea>
                                    <span class="add_product_error" id="description_error" style="color: red;"></span>
                                </div>
                                <hr>
                                <button type="button" id="add_product" class="btn btn-warning d-block mx-auto">Add Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            {{--Modal Update Product--}}
            <div class="modal fade" id="targetmodal_update" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color:antiquewhite">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Product</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form autocomplete="off" id="myform_update">

                                <div class="mb-3">
                                    <input type="hidden" id="hidden_id_update" name="hidden_id_update" value="">{{--For Find Record from db/Primary Key--}}
                                    <label for="category_name_update" class="col-form-label">Update Category Name</label>
                                    <select class="form-control category_name" id="category_name_update" name="category_name_update" required>
                                    </select>
                                    <span class="add_product_update_error" id="category_name_update_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="product_name_update" class="col-form-label">Update Product Name</label>
                                    <input type="text" class="form-control" id="product_name_update" name="product_name_update" value="{{old('product_name_update')}}">
                                    <span class="add_product_update_error" id="product_name_update_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="product_code_update" class="col-form-label">Update Product Code</label>
                                    <input type="text" class="form-control" id="product_code_update" name="product_code_update" value="{{old('product_code_update')}}">
                                    <span class="add_product_update_error" id="product_code_update_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="weight_update" class="col-form-label">Update Weight (g)</label>
                                    <input type="number" class="form-control" id="weight_update" name="weight_update" value="{{old('weight_update')}}">
                                    <span class="add_product_update_error" id="weight_update_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="product_image_update" class="col-form-label">Update Product Image</label>
                                    <input type="file" class="form-control" id="product_image_update" name="product_image_update" value="{{old('product_image_update')}}">
                                    <div class="mt-2" id="product_image_update_show" style="height: 80px; width: 200px;"></div>
                                    <span class="add_product_update_error" id="product_image_update_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity_update" class="col-form-label">Update Quantity</label>
                                    <input type="number" class="form-control" id="quantity_update" name="quantity_update" value="{{old('quantity_update')}}">
                                    <span class="add_product_update_error" id="quantity_update_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="original_price_update" class="col-form-label">Update Original Price</label>
                                    <input type="number" class="form-control" id="original_price_update" name="original_price_update" value="{{old('original_price_update')}}">
                                    <span class="add_product_update_error" id="original_price_update_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="discount_update" class="col-form-label">Update Discount in Percentage (%)</label>
                                    <input type="number" class="form-control" id="discount_update" name="discount_update" value="{{old('discount_update')}}">
                                    <span class="add_product_update_error" id="discount_update_error" style="color: red;"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="description_update" class="col-form-label">Update Description</label>
                                    <textarea id="description_update" class="form-control" name="description_update">{{old('description_update')}}</textarea>
                                    <span class="add_product_update_error" id="description_update_error" style="color: red;"></span>
                                </div>


                                <hr>
                                <button type="button" id="add_product_update" class="btn btn-warning d-block mx-auto">Update Product</button>
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
                    function fetch_category() {
                        $.ajax({
                            url: "{{route('product_category_fetch')}}",
                            type: 'GET',
                            success: function(response) {
                                $('.category_name').empty();
                                $('.category_name').append('<option selected>Select Category</option>'); //defualt option 
                                $.each(response.data, function(index, category) { //also use foreach see fetch all function
                                    $('.category_name').append('<option value="' + category + '">' + category + '</option>');
                                });
                            }
                        });
                    }
                    fetch_category();

                    $('#add_product').click(function(e) {
                        e.preventDefault();
                        $('#add_product_update').prop('disabled', true); // Disable the button
                        $('.spinner-container').show(); //show spinner
                        var formData = new FormData($('#myform')[0]); // Here Target the form directly
                        $.ajax({
                            url: "{{route('productreq')}}",
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
                                    swal("Add Product Status", response.message, "success");
                                    //loaddata();
                                    setTimeout(() => {
                                        loaddata();
                                    }, 5000);
                                    //alert(response.message);
                                    $('#add_product_update').prop('disabled', false); // Re-Enable the button
                                    $('.spinner-container').hide(); //hide the spinner
                                } else if (response.status === 'validateerror') {
                                    $('.add_product_errorr').text(''); // Clear any previous error messages
                                    var errors = response.message;
                                    $.each(errors, function(key, value) {
                                        $('#' + key + '_error').text(value[0]); // Update error message next to the field  //0 optional if miltille error inside same field then 0,1 etc used else for single error inside same field not required
                                    });
                                    $('#add_product_update').prop('disabled', false); // Re-Enable the button
                                    $('.spinner-container').hide(); //hide the spinner
                                }

                            },
                        });

                    });
                    //Display the product data or Fetch record from db
                    function loaddata() {
                        $('.spinner-container').show();
                        $.ajax({
                            url: "{{route('product_fetch_all')}}",
                            type: 'GET',
                            success: function(response) {
                                var respo = response.data;
                                $('#fetch_product').empty();
                                if (respo.length === 0) {
                                    $('#fetch_product').append('<tr><td colspan="6" class ="text-center text-danger" style="font-size: 30px;">No records found</td></tr>');
                                } else {
                                    respo.forEach(function(record) {
                                        // var imageUrl = "__ asset('storage/product_image/') __" + '/' + record.product_image;
                                        $('#fetch_product').append(
                                            '<tr>' +
                                            '<td class="d-none d-sm-table-cell">' + record.id + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.category_name + '</td>' +
                                            '<td>' + record.product_name + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.quantity + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.sale_price + '</td>' +
                                            //'<td>' + '<img src="' + imageUrl + '" alt="load" class="image-zoom" style="width: 50px; height: 50px; border-radius: 50%;">' + '</td>' + // Set width to 100px, height auto, and border-radius to 50%
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
                                        //var button = $(this); // Store reference to the clicked button
                                        //var recordId = button.data('id');
                                        $('.spinner-container').show(); //show spinner
                                        var recordId = $(this).data('id');

                                        // SweetAlert confirmation
                                        swal({
                                            title: "Are you sure?",
                                            text: "You won't be able to recover this Product!",
                                            icon: "warning",
                                            buttons: true,
                                            dangerMode: true,
                                        }).then((willDelete) => {
                                            if (willDelete) {
                                                //deletion via AJAX
                                                $.ajax({
                                                    url: '/admin/productdelete/' + recordId,
                                                    type: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                                    },
                                                    success: function(response) {
                                                        if (response.status === 'success') {
                                                            toastr.success(response.message, "", {
                                                                "toastClass": "toastr-success"
                                                            });
                                                            $(this).closest('tr').hide();
                                                            $('.spinner-container').hide(); //hide the spinner
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
                                            url: '/admin/productupdate/' + recordId,
                                            type: 'GET',
                                            success: function(response) {
                                                var fetch = response.data;
                                                var imageUrl_product = "{{ asset('storage/product_image/') }}" + '/' + fetch.product_image;
                                                $('#hidden_id_update').val(fetch.id);
                                                $('#category_name_update').val(fetch.category_name);
                                                $('#product_name_update').val(fetch.product_name);
                                                $('#product_code_update').val(fetch.product_code);
                                                $('#weight_update').val(fetch.weight);
                                                $('#product_image_update_show').html('<img src="' + imageUrl_product + '" style="max-width: 100%; max-height: 100%;" />');
                                                //For security reason we doesnt put image value inside input file
                                                $('#quantity_update').val(fetch.quantity);
                                                $('#original_price_update').val(fetch.original_price);
                                                $('#discount_update').val(fetch.discount);
                                                $('#description_update').val(fetch.description);
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
                    $('#add_product_update').click(function(e) {
                        e.preventDefault();
                        $('.spinner-container').show(); //show spinner
                        $('#add_product_update').prop('disabled', true); // Disable the button
                        var formData = new FormData($('#myform_update')[0]); // Target the form directly
                        $.ajax({
                            url: "{{route('productupdatestore')}}",
                            type: 'POST',
                            data: formData,
                            contentType: false, //multipart/form-data use
                            processData: false, //by default true we use because normally we send data in form of array ,object but in here we pass data in form of file also
                            headers: { //for csf token also add meta tag at head tag
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    //$('#myform_update').trigger('reset');
                                    swal("Update Product Status", response.message, "success");
                                    //loaddata();
                                    setTimeout(() => {
                                        loaddata();
                                    }, 5000);
                                    //alert(response.message);
                                    $('#add_product_update').prop('disabled', false); //Re-enable the button
                                    $('.spinner-container').hide(); //hide the spinner
                                } else if (response.status === 'validateerror') {
                                    $('.add_product_update_error').text(''); // Clear any previous error messages
                                    var errors = response.message;
                                    $.each(errors, function(key, value) {
                                        $('#' + key + '_error').text(value[0]); // Update error message next to the field  //0 optional if miltille error inside same field then 0,1 etc used else for single error inside same field not required
                                    });
                                    $('#add_product_update').prop('disabled', false); //Re-enable the button
                                    $('.spinner-container').hide(); //hide the spinner
                                }

                            }
                        });
                    });



                });
            </script>

            </x-slot>
</x-dashboard_common>