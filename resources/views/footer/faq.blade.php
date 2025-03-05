<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - PeakPulseMarket</title>
    @include('include.favicon')
    @include('include.fontawesome')
    @include('include.bootstrap')
    @include('include.spinner')
</head>

<body>
    {{--navbar--}}
    <x-navbar />
    <div class="dynamic-search-result-show-then-hide-this">{{--Inside navbar Component--}}
        <h1 class="text-center">Frequently Asked Questions</h1>
        {{--Accordian Start here and then used from componenet--}}
        <div class="container mt-4 mb-4">
            <div class="accordion" id="accordionExample">

                {{--Dynamic Component--}}
                <x-dynamic_faq question="How can I Registered ?" answer="You can register from signup page of Peak Pulse Market;  " dividd="first" />
                <x-dynamic_faq question="How can I order from the website?" answer="Ordering from our website is simple. Just follow these steps: Browse our products and select the ones you want. Add them to your cart. Review your cart and proceed to checkout after log in. Enter your shipping address and payment information. Complete your order. If you have any questions, feel free to contact us." dividd="second" />
                <x-dynamic_faq question="What payment methods do you accept?" answer="We accept all major payment methods including Debit/Credit Cards, UPI, debit cards, and net banking, commonly used , for seamless transactions on our website. " dividd="third" />
                <x-dynamic_faq question="Why i am unable to place order?" answer="This could be due to the following issues: Payment decline, item out of stock, technical or internet connectivity problems, or undeliverable to the selected location/pin code. " dividd="fourth" />
                <x-dynamic_faq question="How to track Orders ?" answer="Once your order is shipped, you will receive a tracking link via email and SMS from the courier aggregator, allowing you to monitor the delivery status of your package. Additionally, you can also track your order on our website inside order section" dividd="fifth" />
                <x-dynamic_faq question="What should I do if I receive a defective, damaged, or incorrect product?" answer=" If you receive a defective, damaged, or incorrect product, please contact our customer support team immediately. We will promptly assist you in resolving the issue by arranging for a replacement or providing a refund, as necessary. Your satisfaction is our priority, and we strive to ensure that you receive the correct and high-quality products you ordered." dividd="sixth" />
                <x-dynamic_faq question="How are refunds processed?" answer=" Refunds are credited back to the original payment method used for the purchase. If you made your purchase with a credit card, the refund will be issued back to that same credit card. For other payment methods such as PayPal or UPI, the refund will be returned to the respective account. Refunds are typically processed within [3-5 days not bank holidays] days after the return is received and inspected. If you have any questions or concerns regarding your refund, please contact our customer support team for assistance." dividd="seventh" />
                <x-dynamic_faq question=" How can I get additional help or assistance?" answer="If you need further assistance, you can reach out to us. Our customer support team is available to address any questions or concerns you may have regarding your order, products, or any other inquiries. We're here to help!" dividd="eight" />

            </div>
        </div>
    </div>
    <x-footer />
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}

</body>

</html>