<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$dynamic_title_top}}</title> {{--Use Slot Here--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="csrf-token" content="{{ csrf_token() }}"><!--the second one is variable and the value comes inside this variable is from ajax-->
    @include('include.favicon')
    @include('include.bootstrap')
    @include('include.fontawesome')
    @include('include.spinner')

    <style>
        .navbar {
            max-height: 15vh;
            height: auto;
        }

        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .navbar img {
            height: 60px;
            width: auto;
        }

        @media (max-width: 768px) {
            .navbar img {
                height: 40px;
                margin-top: 5px;

            }
        }


        .inherit {
            height: inherit;
            /* Inherit height from parent */
        }

        .dropdown-item:active {
            background-color: var(--bs-warning) !important;
            color: black !important;
        }
    </style>
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a href="{{route('dashboard')}}" class="inherit">
                <img src="{{url('Business_Logo/logo1.png')}}" alt="Business Logo"></a>
            <h3 class="text-light">Admin Panel</h3>
            <h6><a class="dropdown-item text-light" href="{{route('signout')}}"><i class="fa-solid fa-right-from-bracket"></i> Sign out</a></h6>
        </div>
    </nav>


    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="#" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline">Menu</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <a href="{{route('dashboard')}}" class="nav-link align-middle px-0 text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Home"> <i class="fa-solid fa-house"></i> <span class="ms-1 d-none d-sm-inline">Home</span></a>
                        </li>

                        <li>
                            <a href="{{route('category')}}" class="nav-link px-0 align-middle text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Category"><i class="fa-solid fa-layer-group"></i> <span class="ms-1 d-none d-sm-inline">Category</span></a>
                        </li>

                        <li>
                            <a href="{{route('product')}}" class="nav-link px-0 align-middle text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Product"><i class="fa-brands fa-product-hunt"></i> <span class="ms-1 d-none d-sm-inline">Product</span></a>
                        </li>

                        <li>
                            <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle text-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Booking"><i class="fa-solid fa-circle-chevron-down"></i></i> <span class="ms-1 d-none d-sm-inline">Booking</span> </a>
                            <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                                <li class="w-100">
                                    <a href="{{route('newbooking')}}" class="nav-link px-0 text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="New Booking"><i class="fa-solid fa-circle-plus"></i> <span class="d-none d-sm-inline">New Booking</span> </a>
                                </li>

                                <li>
                                    <a href="#submenu8" data-bs-toggle="collapse" class="nav-link px-0 align-middle text-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Approved"><i class="fa-solid fa-circle-chevron-down"></i> <span class="ms-1 d-none d-sm-inline">Approved</span> </a>
                                    <ul class="collapse nav flex-column ms-1" id="submenu8" data-bs-parent="#submenu3">

                                        <li class="w-100">
                                            <a href="{{route('approved_preparing')}}" class="nav-link px-0 text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Preparing"><i class="fa-solid fa-box-open"></i> <span class="d-none d-sm-inline">Preparing</span> </a>
                                        </li>
                                        <li class="w-100">
                                            <a href="{{route('approved_dispatched')}}" class="nav-link px-0 text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Dispatched"><i class="fa-solid fa-truck-fast"></i> <span class="d-none d-sm-inline">Dispatched</span> </a>
                                        </li>
                                        <li class="w-100">
                                            <a href="{{route('cancel_booking')}}" class="nav-link px-0 text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancelled Booking"><i class="fa-solid fa-rotate-left"></i> <span class="d-none d-sm-inline">Cancelled</span> </a>
                                        </li>
                                        <li class="w-100">
                                            <a href="{{route('approved_delivered')}}" class="nav-link px-0 text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Delivered"><i class="fa-solid fa-handshake"></i> <span class="d-none d-sm-inline">Delivered</span> </a>
                                        </li>
                                        <li class="w-100">
                                            <a href="{{route('return')}}" class="nav-link px-0 text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Return"><i class="fa-solid fa-handshake"></i> <span class="d-none d-sm-inline">Returning</span> </a>
                                        </li>


                                    </ul>
                                </li>

                                <li class="w-100">
                                    <a href="{{route('refunded')}}" class="nav-link px-0 text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Refunded"><i class="fa-solid fa-circle-xmark"></i> <span class="d-none d-sm-inline">Refunded</span></a>
                                </li>
                                <li class="w-100">
                                    <a href="{{route('payment_failed_booking')}}" class="nav-link px-0 text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Payment Failed"><i class="fa-solid fa-xmark"></i> <span class="d-none d-sm-inline">Payment Failed</span></a>
                                </li>

                            </ul>
                        </li>

                        <li>
                            <a href="{{route('user')}}" class="nav-link px-0 align-middle text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="User"><i class="fa-solid fa-user-tie"></i> <span class="ms-1 d-none d-sm-inline">User</span> </a>
                        </li>
                        <li>
                            <a href="{{route('manage_review')}}" class="nav-link px-0 align-middle text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Review"><i class="fa-solid fa-comment"></i> <span class="ms-1 d-none d-sm-inline">Review</span> </a>
                        </li>
                        <li>
                            <a href="{{route('complains')}}" class="nav-link px-0 align-middle text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Complains"><i class="fa-solid fa-question"></i> <span class="ms-1 d-none d-sm-inline">Complains</span> </a>
                        </li>
                        <li>
                            <a href="{{route('frontcontent')}}" class="nav-link px-0 align-middle text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Front Content"><i class="fa-solid fa-camera-rotate"></i> <span class="ms-1 d-none d-sm-inline">Front Content</span> </a>
                        </li>
                        <li>
                            <a href="{{route('notification_form')}}" class="nav-link px-0 align-middle text-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Notification"><i class="fa-solid fa-bell"></i> <span class="ms-1 d-none d-sm-inline">Add Notification</span></a>
                        </li>

                        <li>
                            <hr>
                            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{url('Business_Logo/logo2.png')}}" alt="hugenerd" width="30" height="30" class="rounded-circle">
                                <span class="d-none d-sm-inline mx-1">Admin</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="{{route('profileupdate')}}"><i class="fa-solid fa-address-card"></i> Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{route('signout')}}"><i class="fa-solid fa-right-from-bracket"></i> Sign out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="col py-3">
                {{$section_dynamic_content}}
                {{--Before here this content used but now here data pass by slot--}}
            </div>



        </div>
    </div>



</body>

</html>