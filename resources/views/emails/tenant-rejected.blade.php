<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status Update</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eaeaea;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px 0;
        }
        h1 {
            color: #4473be;
            font-size: 24px;
            margin-top: 0;
        }
        .reason {
            background-color: #f9f9f9;
            border-left: 4px solid #888;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            background-color: #4473be;
            color: white !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 50px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
            color: #888;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Status Update</h1>
        </div>

        <div class="content">
            <p>Hello {{ $application->full_name }},</p>

            <p>We've reviewed your application for <strong>{{ $application->company_name }}</strong>.</p>

            <p>After careful consideration, we regret to inform you that we are unable to approve your application at this time.</p>

            <div class="reason">
                <h3>Reason for Decision</h3>
                <p>{{ $application->notes }}</p>
            </div>

            <p>If you would like to discuss this further or provide additional information, please feel free to contact our support team by replying to this email.</p>

            <p>Thank you for your interest in our platform.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
