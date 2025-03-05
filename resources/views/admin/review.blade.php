<x-dashboard_common>

    <x-slot:dynamic_title_top>
        Manage Review - PeakPulseMarket
        </x-slot>

        <x-slot:section_dynamic_content>
            <style>
                .toastr-success {
                    background-color: green !important;
                }

                .toastr-error {
                    background-color: red !important;
                }
            </style>
            <h1 class="text-center text-danger">Manage Reviews</h1>
            <table class="table  table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="d-none d-sm-table-cell">S.No</th>
                        <th scope="col" class="d-none d-sm-table-cell">User_id</th>
                        <th scope="col" class="d-none d-sm-table-cell">Product_id</th>
                        <th scope="col">Rating</th>
                        <th scope="col" class="d-none d-sm-table-cell">Review</th>{{--Hide the Column on Small Screen--}}
                        <th scope="col">Status</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    {{-- <tr> 
                <td>1</td>
                <td class ="d-none d-sm-table-cell">your image</td>
                <td>Sam </td>
                <td>I like the service offered by you </td>
                <td><input type="checkbox"></td>
          </tr>
          --}}
                </tbody>
            </table>

            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                $(document).ready(function() {
                    function fetchReviewData() {
                        $('.spinner-container').show();
                        $.ajax({
                            url: '{{ route("manage_review_fetch") }}',
                            type: 'GET',
                            success: function(data) {
                                // Clear existing table rows
                                $('#table-body').empty();
                                if (data.rec.length === 0) {
                                    $('#table-body').append('<tr><td colspan="7" class ="text-center text-danger" style="font-size: 30px;">No Review found</td></tr>');
                                } else {
                                    //now fetch and attach to tbody by id selector
                                    data.rec.forEach(function(record) { //if object then data.send.forEach(record) or var x=data.send;
                                        // var checkboxHtml = record.status == 1 ? '<input type="checkbox" name="checkbox" id="checkbox" value="1" checked>' : '<input type="checkbox" id="checkbox" name="checkbox" value="0">';          
                                        /*var isChecked = record.status == 1 ? 'checked' : '';
                                        var checkboxHtml = '<input type="checkbox" name="status" class="status-checkbox" data-id="' + record.id + '" ' + isChecked + '>';*/
                                        var checkboxHtml = '<input type="checkbox" name="status" class="status-checkbox" data-id="' + record.id + '" ' + (record.status == 1 ? 'checked' : '') + '>';
                                        var row = '<tr>' +
                                            '<td class="d-none d-sm-table-cell">' + record.id + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.tbluser_id + '</td>' +
                                            '<td class="d-none d-sm-table-cell"><a href="/product_checkout/' + record.tblproduct_id + '" class="product-link" target="_blanck">' + record.tblproduct_id + '</a></td>' +
                                            '<td>' + record.rating + '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.review + '</td>' +
                                            '<td>' + checkboxHtml + '<p class="text-danger checkbox-record" style="display: inline;">(&check;:active)</p></td>' +
                                            '<td>' + '<a href="" class="delete-record text-danger" data-id="' + record.id + '" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-archive"></i></a>' + '</td>' +
                                            '</tr>';
                                        $('#table-body').append(row);
                                    });

                                }

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
                                        text: "To Delete this Review Details!",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                    }).then((willDelete) => {
                                        if (willDelete) {
                                            //deletion via AJAX
                                            $.ajax({
                                                url: '/admin/manage_review_delete/' + recordId,
                                                type: 'DELETE',
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                                            swal("Cancelled", "Delete Review !", "info");
                                            $('.spinner-container').hide(); //hide the spinner
                                        }
                                    });
                                });


                            },
                            complete: function() {
                                $('.spinner-container').hide(); // Hide spinner after data is loaded
                            },
                            error: function() {
                                $('.spinner-container').hide(); // Hide spinner in case of error
                            } 

                        });
                    }
                    fetchReviewData();
                    //setInterval(fetchReviewData, 9000);
                });
                //Due to  event handlers becuase in delete or button it directly to that but here not 
                $(document).on('click', '.status-checkbox', function() {
                    $('.spinner-container').show(); //show spinner
                    var checkbox = $(this);
                    var status = checkbox.is(':checked') ? 1 : 0; //value toggle send 0 and 1
                    var recordId = checkbox.data('id'); //for identify the id from database which change
                    $.ajax({
                        url: "{{route('reviewstatus')}}",
                        type: 'POST',
                        data: {
                            status: status,
                            recordId: recordId,
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
                            } else if (response.status === 'validateerror') {
                                toastr.error(response.message, "", {
                                    "toastClass": "toastr-error"
                                });
                                $('.spinner-container').hide(); //hide the spinner
                            }
                        }

                    });

                });
            </script>

            </x-slot>
</x-dashboard_common>