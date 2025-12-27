<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Code</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 40px 30px;
            text-align: center;
        }

        .otp-box {
            background-color: #f9fafb;
            border: 2px dashed #f97316;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }

        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #f97316;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }

        .message {
            color: #374151;
            font-size: 16px;
            line-height: 1.6;
            margin: 20px 0;
        }

        .arabic {
            direction: rtl;
            font-size: 16px;
            color: #6b7280;
            margin-top: 20px;
        }

        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🔐 OTP Verification Code</h1>
        </div>

        <div class="content">
            <p class="message">
                <strong>Your One-Time Password (OTP) is:</strong>
            </p>

            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <p class="message">
                This code will expire in <strong>10 minutes</strong>.<br>
                Please do not share this code with anyone.
            </p>

            <div class="warning">
                <strong>⚠️ Security Notice:</strong><br>
                If you didn't request this code, please ignore this email and ensure your account is secure.
            </div>

            <div class="arabic">
                <p><strong>رمز التحقق الخاص بك هو:</strong></p>
                <p style="font-size: 24px; font-weight: bold; color: #f97316;">{{ $otp }}</p>
                <p>سينتهي هذا الرمز خلال <strong>10 دقائق</strong>.<br>
                    الرجاء عدم مشاركة هذا الرمز مع أي شخص.</p>
            </div>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>

</html>
