<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - PeakPulseMarket</title>
    @include('include.favicon')
    @include('include.bootstrap')
    @include('include.fontawesome')
    @include('include.spinner')
    <style>
        .center-text {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60vh;
            text-align: center;
        }
    </style>
</head>

<body class="bg-light">
    {{-- Navbar --}}
    <x-navbar />

    {{-- Main Content --}}
    <div class="container">
        <div class="row">
            <div class="col-md-12 center-text">
                <div>
                    <h1 class="text-danger">This Link is Expired</h1>
                    <p class="mt-3">
                        Unfortunately, the password reset link has expired. Please click the button below to generate a new password reset link.
                    </p>
                    <a href="{{ route('password.request') }}" class="btn btn-warning mt-3">
                        Generate New Password Reset Link
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <x-footer />
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
</body>

</html>