<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - PeakPulseMarket</title>
    @include('include.favicon')
    @include('include.fontawesome')
    @include('include.bootstrap')
    @include('include.spinner')
    <style>
        body {
            background-color: #f8f9fa;
            /* Set background color */
        }

        .container-box {
            width: 100%;
            height: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
            /* Set background color */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Add a subtle box shadow */
        }
    </style>
</head>

<body>
    {{--navbar--}}
    <x-navbar />
    <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}
        <div class="container mb-4 mt-4">
            <div class="container-box">
                <h1 class="text-center">Payments</h1>
                <p>
                    Welcome to Peak Pulse Market, your premier destination for online pulse, spice, pickle, and snack shopping! We offer a seamless payment experience to ensure your transactions are secure and convenient. Here's everything you need to know about making payments on our platform:<br><br>
                    <strong>Accepted Payment Methods:</strong><br>
                    At Peak Pulse Market, we understand the importance of offering diverse payment options. Whether you prefer traditional methods or the latest digital wallets, we've got you covered. Our accepted payment methods include:<br>
                    <strong>**Credit/Debit Cards**</strong> We accept Visa, MasterCard, Maestro, and American Express credit/debit cards issued in India. Simply enter your card details during checkout for secure transaction.<br>
                    <strong>**Internet Banking**</strong> Enjoy the convenience of paying directly from your bank account using Internet Banking. We support transactions from a wide range of banks, ensuring a hassle-free payment process.<br>
                    <strong>**Cash on Delivery (C-o-D)**</strong> For those who prefer to pay in cash, we offer Cash on Delivery (C-o-D) service. Simply select this option during checkout, and pay at your doorstep when your order arrives.<br>
                    <strong>**Wallets**</strong> Take advantage of popular digital wallets for quick and easy payments. We accept all major wallets supported in India, providing added flexibility for our customers.<br><br>
                    <strong>Transparent Pricing:</strong>
                    No hidden charges. Prices displayed are final, inclusive of all taxes and fees.<br>
                    <strong>Secure Transactions:</strong>
                    Advanced encryption technology ensures secure payments.<br>
                    <strong>Fraud Prevention Measures:</strong>
                    Continuous monitoring and additional verification for suspicious activities.<br>
                    <strong>Customer Support:</strong>
                    Reach out for assistance via phone, email.<br>
                </p>

            </div>
        </div>
    </div>
    <x-footer />
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
</body>

</html>