<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Approved</title>
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
        .credentials {
            background-color: #f5f8ff;
            border-left: 4px solid #4473be;
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
        .domain {
            font-weight: bold;
            color: #4473be;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Approved! 🎉</h1>
        </div>

        <div class="content">
            <p>Hello {{ $application->full_name }},</p>

            <p>We're pleased to inform you that your application for <strong>{{ $application->company_name }}</strong> has been approved.</p>

            <p>Your tenant has been created and is now ready to use.</p>

            <div class="credentials">
                <h3>Login Credentials</h3>
                <p><strong>Email:</strong> {{ $application->email }}<br>
                <strong>Password:</strong> {{ $password }}</p>
                <p><em>Please change your password after your first login for security reasons.</em></p>
            </div>

            <p>Your tenant domain is: <span class="domain">{{ $domain }}</span></p>

            <div style="text-align: center;">
                <a href="https://{{ $domain }}" class="button">Access Your Tenant</a>
            </div>

            <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

            <p>Thank you for choosing our platform!</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
