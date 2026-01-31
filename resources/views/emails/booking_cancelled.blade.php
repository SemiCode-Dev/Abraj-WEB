<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Booking Cancelled</title>
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
        }

        .details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }

        .refund-note {
            font-weight: bold;
            color: #d9534f;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Booking Cancelled</h2>

        <p>Dear {{ $booking->guest_name }},</p>

        <p>We are writing to inform you that your booking for <strong>{{ $booking->hotel_name }}</strong> has been
            cancelled by the administrator.</p>

        <div class="details">
            <p><strong>Booking Details:</strong></p>
            <ul>
                <li><strong>Hotel:</strong> {{ $booking->hotel_name }}</li>
                <li><strong>Check-in:</strong> {{ $booking->check_in->format('Y-m-d') }}</li>
                <li><strong>Check-out:</strong> {{ $booking->check_out->format('Y-m-d') }}</li>
            </ul>
        </div>

        <p class="refund-note">
            If you would like to request a refund, please contact us at +966 9200 15728.
        </p>

        <p>If you have any other questions, please contact our support team immediately.</p>

        <p>
            Thanks,<br>
            Abraj Stay
        </p>
    </div>
</body>

</html>
