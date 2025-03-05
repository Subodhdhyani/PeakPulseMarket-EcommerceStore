<x-dashboard_common>
    <x-slot:dynamic_title_top>
       Manage Notification - PeakPulseMarket
        </x-slot>
        <x-slot:section_dynamic_content>
            <style>
                .image-zoom {
                    transition: transform 0.3s ease;
                }

                .image-zoom:hover {
                    transform: scale(4.2);
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
                <h1 class="text-center text-danger">Manage Notification for all User</h1>
                <div class="mt-2 mb-2" style="text-align: right;">
                    <a href="{{route('notification_form')}}">
                        <button type="button" class="btn btn-warning">
                            Back
                        </button>
                    </a>
                </div>
                {{--Table to show Booking Record--}}
                <div>
                    <table class="table  table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="d-none d-sm-table-cell">Id (New First)</th>
                                <th scope="col">Title</th>
                                <th scope="col">Message</th>
                                <th scope="col" class="d-none d-sm-table-cell">Image</th>
                                <th scope="col" class="d-none d-sm-table-cell">Creation Time</th>
                                <th scope="col">Operation</th>
                            </tr>
                        </thead>
                        <tbody id="fetch_notification">
                            <!-- Table body content comes here by ajax -->
                        </tbody>
                    </table>
                </div>
            </div>

            {{--Add jquery  and toastr--}}
            <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                $(document).ready(function() {
                    function loaddata() {
                        $('.spinner-container').show();
                        $.ajax({
                            url: "{{route('manage_fetch_notification')}}",
                            type: 'GET',
                            success: function(response) {
                                var respo = response.message;
                                $('#fetch_notification').empty();
                                if (respo.length === 0) {
                                    $('#fetch_notification').append('<tr><td colspan="6" class ="text-center text-danger" style="font-size: 30px;">No Notification Found</td></tr>');
                                } else {
                                    respo.forEach(function(record) {
                                        $('#fetch_notification').append(
                                            '<tr>' +
                                            '<td class="d-none d-sm-table-cell">' + record.serial + '</td>' +
                                            '<td>' + record.title + '</td>' +
                                            '<td>' + record.message + '</td>' +
                                            //'<td class="d-none d-sm-table-cell"><img src="' + record.image + '" alt="Notification Image" class="image-zoom" style="width: 50px; height: 50px; border-radius: 50%;"></td>' +
                                            '<td class="d-none d-sm-table-cell">' +
                                            (record.image ?
                                                '<img src="' + record.image + '" alt="Notification Image" class="image-zoom" style="width: 50px; height: 50px; border-radius: 50%;">' :
                                                '<p>No Image</p>') +
                                            '</td>' +
                                            '<td class="d-none d-sm-table-cell">' + record.created_at + '</td>' +
                                            '<td>' +
                                            '<a href="" class="delete_notification_action text-dark" data-id="' + record.id + '" data-bs-toggle="tooltip" title="Delete Notification"><i class="bi bi-archive-fill"></i></a>&emsp;' +

                                            '</td>' +
                                            '</tr>'
                                        )
                                    });


                                    //Delete Notification
                                    $('.delete_notification_action').click(function(e) {
                                        e.preventDefault();
                                        $('.spinner-container').show(); //show spinner
                                        var recordId = $(this).data('id');

                                        // SweetAlert confirmation
                                        swal({
                                            title: "Are you sure?",
                                            text: "You won't be able to recover this Notification!",
                                            icon: "warning",
                                            buttons: true,
                                            dangerMode: true,
                                        }).then((willDelete) => {
                                            if (willDelete) {
                                                //deletion via AJAX
                                                $.ajax({
                                                    url: '/admin/delete_notification/' + recordId,
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
                                                swal("Cancelled", "Deletion Cancelled !", "info");
                                                $('.spinner-container').hide(); //hide the spinner
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
                        })
                    }
                    loaddata();
                });
            </script>

            </x-slot>
</x-dashboard_common>