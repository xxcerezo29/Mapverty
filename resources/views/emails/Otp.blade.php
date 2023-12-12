<!DOCTYPE html>
<html>
<head>
    <title>Your OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #007bff;
        }
        p {
            margin-bottom: 15px;
        }
        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Your OTP</h1>
    <p>Dear User,</p>
    <p>Your OTP is: <strong>{{ $otp }}</strong></p>
    <p>Please use this OTP for authentication.</p>
    <p>Thank you!</p>
</div>
</body>
</html>
