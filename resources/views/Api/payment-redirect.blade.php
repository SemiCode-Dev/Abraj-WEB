<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>payFort</title>
    <style>
        body {
            background: linear-gradient(180deg, #1B7C6E 0%, #352B88 100%);
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: sans-serif;
            overflow: hidden;
        }
    </style>
</head>

<body onload="document.getElementById('paymentForm').submit();">

    <form id="paymentForm" method="POST" action="{{ $payment_url }}">
        @foreach ($payment_data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
</body>

</html>
