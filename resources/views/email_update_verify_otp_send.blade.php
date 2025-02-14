<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP for Email Update - PeakPulseMarket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #ffc107;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .header img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .content h1 {
            color: black;
            margin-bottom: 20px;
        }

        .content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
        }

        .otp {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin: 20px 0;
        }

        .footer {
            background-color: #ffc107;
            color: black;
            text-align: center;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 0 0 10px 10px;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }

        .email-content p {
            color: #333;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <!-- Email Container -->
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="https://i.ibb.co/H2jfkgd/logo1.png" alt="Logo">
        </div>

        <!-- Content -->
        <div class="content">
            <p>Dear User,</p>
            <p>We have received a request to update your email address. Please use the following OTP to verify your email address:</p>
            <div class="otp">{{ $otp }}</div>
            <p>If you did not request this change, please ignore this email.</p>
            <p>Thank you!</p>
            <p>Peak Pulse Market Team</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Peak Pulse Market. All rights reserved.</p>
        </div>
    </div>
</body>

</html>