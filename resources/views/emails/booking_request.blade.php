<!DOCTYPE html>
<html>

<head>
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
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .row {
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
            width: 150px;
            display: inline-block;
        }

        .value {
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>New {{ $type }} Request</h2>
        </div>

        <div class="content">
            <p>A new booking request has been received with the following details:</p>

            @foreach ($data as $key => $value)
                @if (!empty($value) && !is_array($value) && !is_object($value))
                    <div class="row">
                        <span class="label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                        <span class="value">{{ $value }}</span>
                    </div>
                @endif
            @endforeach
        </div>

        <div style="margin-top: 20px; font-size: 12px; color: #777;">
            <p>This email was sent automatically from Abraj Stay system.</p>
        </div>
    </div>
</body>

</html>
