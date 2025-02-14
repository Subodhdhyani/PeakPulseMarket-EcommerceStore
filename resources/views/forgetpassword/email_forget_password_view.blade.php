<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - PeakPulseMarket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

        .email-header {
            background-color: #ffc107;
            text-align: center;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }

        .email-header img {
            width: 100px;
            height: auto;
        }

        .email-body h2 {
            color: black;
            margin-bottom: 20px;
        }

        .reset-button {
            display: block;
            background-color: #ffc107;
            color: black;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            width: calc(100% - 40px);
            margin: 10px auto;
            max-width: 560px;
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
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="email-header">
            <img src="https://i.ibb.co/H2jfkgd/logo1.png" alt="Logo"> <!-- Put image on online server -->
        </div>

        <!-- Email Body -->
        <div class="email-body">
            <h2>Reset Your Password</h2>
            <div class="email-content">
                <p>You requested to reset your password. Click the button below to reset it:</p>
                <a href="{{ $resetLink }}" class="reset-button">Reset Password</a>
            </div>
            <div class="email-content">
                <p>If the button doesn't work, copy and paste the link below into your browser:</p>
                <p><a href="{{ $resetLink }}" style="color:blue;">{{ $resetLink }}</a></p>
            </div>
            <p>If you did not request this, you can safely ignore this email.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Peak Pulse Market. All rights reserved.</p>
        </div>
    </div>
</body>

</html>