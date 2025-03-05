<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy - PeakPulseMarket</title>
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
                <h1 class="text-center"> Privacy Policy PeakPulseMarket</h1>



                We deeply value your trust in Peak Pulse Market and understand the significance of safeguarding your information and ensuring secure transactions. This Privacy Policy outlines how we, Peak Pulse Market (referred to as "we," "our," or "us"), collect, utilize, share, and process your personal data through our online ecommerce store, PeakPulseMarket.com<br>
                By accessing or using our Platform, providing your information, or engaging with our products and services, you expressly agree to adhere to the terms and conditions outlined in this Privacy Policy. If you do not consent to these terms, please refrain from using or accessing our Platform.<br><br>
                <strong>Collection of Your Information</strong><br>
                When you interact with our Platform, we gather and retain the information you provide to us. While certain sections of the Platform can be browsed without sharing personal information, certain activities such as making a purchase or participating in contests necessitate the collection of personal data.<br>

                We may track your browsing behavior, preferences, and voluntarily provided information to enhance our understanding of user demographics, interests, and behavior. This aggregated information aids us in improving our services and tailoring them to better serve our users.<br>

                <ul>Information we may collect includes but is not limited to:
                    <li>Contact details (such as email address, delivery address, phone number)</li>
                    <li>Payment information (credit card/debit card details)</li>
                    <li>Any additional information provided during account setup, transactions, or participation in events/contests</li>
                </ul>

                <strong>Use of Your Information</strong><br>
                <ul>We utilize your personal data to:
                    <li>Fulfill orders, deliver products/services, and process payments</li>
                    <li>Communicate with you regarding orders, products/services, and promotional offers</li>
                    <li>Customize your experience, including providing personalized content and offers based on your preferences and past interactions</li>
                    <li>Conduct internal research, analyze user demographics, and improve our product and service offerings</li>
                </ul>

                <strong>Sharing of Personal Data</strong><br>
                <ul>We may share your personal data with:
                    <li>Third-party service providers, affiliates, and business partners to facilitate product/service delivery and enhance customer experience</li>
                    <li>External entities for legal compliance, fraud prevention, and enforcement of terms and conditions</li>
                    <li>In the event of a merger, acquisition, or reorganization, your personal data may be shared with the involved business entity while adhering to this Privacy Policy</li>
                </ul>

                <strong>Security Measures</strong><br>
                We maintain reasonable physical, electronic, and procedural safeguards to protect your information from unauthorized access or disclosure. However, users should acknowledge the inherent risks associated with internet-based data transmission.<br><br>

                <strong>Your Rights</strong><br>
                You have the right to access, correct, and update your personal data through the functionalities provided on the Platform. Additionally, you can withdraw consent for specific data processing activities, subject to verification and compliance with applicable laws.<br><br>

                <strong>Consent</strong><br>
                By utilizing our Platform and providing personal data, you consent to the collection, usage, storage, and processing of your information in accordance with this Privacy Policy.<br><br>

                <strong>Changes to Privacy Policy</strong><br>
                Please review our Privacy Policy periodically, as we may update it to reflect changes in information practices. We will notify you of significant changes through appropriate channels.<br><br>

                <strong>Contact Information</strong><br>
                If you have any queries, concerns, or complaints regarding the collection or usage of your personal data, please reach out to our Grievance Officer at privacy@peakpulsemarket.com.<br><br>
                <ins>*Note:</ins> This Privacy Policy is effective as of <strong>2025</strong>, and any updates will be communicated accordingly.

            </div>
        </div>
    </div>
    <x-footer />
    <script src="{{url('js/spinner.js')}}"></script>{{--Show spinner when page load--}}
</body>

</html>