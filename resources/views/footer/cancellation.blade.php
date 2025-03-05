<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return & Cancellation - PeakPulseMarket</title>
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
                <h1 class="text-center">Return & Cancellation Policy</h1>
                <p>
                    <strong>Cancellation:</strong>
                    You can cancel your order before it is shipped by contacting our customer support team or by Myorder section.<br>

                    <strong>Return:</strong>
                    If you receive a damaged or defective item, or if it's not as described, you can initiate a return within 7 days of delivery. Please ensure that the product is unused and in its original packaging with all tags intact.<br>

                    <strong>Process:</strong>
                    To initiate a return, contact our customer support team and also do refund from order section.If you select customer support team for return they will guide you through the process and provide you with a return authorization.<br>

                    <strong>Pick-Up:</strong>
                    For eligible returns, our logistics partner will arrange for a pick-up at your provided address. Please ensure that someone is available to hand over the package during the scheduled pick-up time.<br>

                    <strong>Refund:</strong>
                    Once the returned item is received and inspected, we will initiate the refund process. Refunds are processed within 7 days and are credited back to the original payment method used for the purchase.<br>

                    <strong>Exceptions:</strong>
                    Please note that certain products may not be eligible for return due to hygiene reasons or other restrictions. Additionally, personalized or customized items cannot be returned unless they are defective.<br>

                    <strong>Contact Us:</strong>
                    If you have any questions or need further assistance regarding cancellations or returns, feel free to reach out to our customer support team. We are here to help you!<br>
                </p>
            </div>
        </div>
    </div>

    <x-footer />
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
</body>

</html>